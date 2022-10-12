<?php

use unt\exceptions\APIException;
use unt\objects\APIResponse;
use unt\objects\BaseAPIMethod;
use unt\objects\Context;
use unt\objects\Letter;
use unt\objects\View;

return new class extends BaseAPIMethod {

    private ?string $session_id;

    private string $first_name_preg = "/[^a-zA-Zа-яА-ЯёЁ'-]/ui";

    public function __construct()
    {
        parent::__construct('account.create', BaseAPIMethod::GROUP_DEFAULT, \unt\objects\BaseAPIMethodParams::create()
            ->addParam('session_id', \unt\objects\BaseAPIMethodParams::PARAM_STRING)
            ->addParam('first_name', \unt\objects\BaseAPIMethodParams::PARAM_STRING)
            ->addParam('last_name', \unt\objects\BaseAPIMethodParams::PARAM_STRING)
            ->addParam('email', \unt\objects\BaseAPIMethodParams::PARAM_STRING)
            ->addParam('code', \unt\objects\BaseAPIMethodParams::PARAM_INT)
            ->addParam('g', \unt\objects\BaseAPIMethodParams::PARAM_INT)
            ->addParam('password', \unt\objects\BaseAPIMethodParams::PARAM_STRING)
        );
    }

    public function initSession (): int
    {
        $this->session_id = $this->getParams()->getValues()['session_id'];
        if (!$this->session_id)
            $this->session_id = session_create_id('');

        session_write_close();
        session_id($this->session_id);
        session_start();

        return isset($_SESSION['stage']) ? intval($_SESSION['stage']) : 0;
    }

    public function nextStage (): int
    {
        $stage = intval($_SESSION['stage']) + 1;
        if ($stage >= 5) $stage = 5;

        $_SESSION['stage'] = $stage;
        session_write_close();

        return (int) $_SESSION['stage'];
    }

    /**
     * @param Context|null $context
     * @param callable|null $callback
     * @return BaseAPIMethod
     */
    public function run(?Context $context, ?callable $callback = NULL): BaseAPIMethod
    {
        $stage = $this->initSession();
        if ($stage < 0 || $stage > 4)
            throw new APIException('Current stage is invalid', 100);

        $response = [];

        if ($stage === 0) {
            $first_name = $this->getParams()->getValues()['first_name'];
            $last_name = $this->getParams()->getValues()['last_name'];

            if (UntEngine::get()->isEmptyString($first_name) || strlen($first_name) > 32 || preg_match($this->first_name_preg, $first_name))
                throw new APIException('First name is invalid', 101);
            if (UntEngine::get()->isEmptyString($last_name) || strlen($last_name) > 32 || preg_match($this->first_name_preg, $last_name))
                throw new APIException('Last name is invalid', 102);

            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
        }
        if ($stage === 1) {
            $email = $this->getParams()->getValues()['email'];
            if (UntEngine::get()->isEmptyString($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
                throw new APIException('E-mail is invalid', 103);

            $code = rand(111111, 999999);
            $_SESSION['code'] = $code;
            $_SESSION['email'] = $email;

            if ($_SESSION['cool_down'] > time())
                throw new APIException('Time to send next letter is not reached', 105, [
                    'remaining_time' => ($_SESSION['cool_down'] - time())
                ]);

            $view = new View(__DIR__ . '/../../../dev/views/mail_register.php', [
                'first_name' => $_SESSION['first_name'],
                'code' => $code
            ]);

            $letter = Letter::create(\unt\lang\Language::get()->email_welcome, $view)->to($email);
            if (!$letter->send())
                throw new APIException('Failed to validate session.', 104);

            $_SESSION['cool_down'] = time() + 300;
        }
        if ($stage === 3) {
            $code = (int)$this->getParams()->getValues()['code'];

            $current_code = intval($_SESSION['code']);
            $email        = strval($_SESSION['email']);

            if ($code !== $current_code)
                throw new APIException('Provided code is incorrect', 110);

            $response['email'] = $email;
        }
        if ($stage === 4) {
            $password = $this->getParams()->getValues()['password'];
            $gender   = (int)$this->getParams()->getValues()['g'] != 0 ? 1 : 0;

            if (UntEngine::get()->isEmptyString($password) || strlen($password) < 6 || strlen($password) > 60)
                throw new APIException('Password can not be empty and must be more then 6 symbols and less then 60', 111);

            $_SESSION['password_hash'] = password_hash($password, PASSWORD_DEFAULT);

            $first_name = strval($_SESSION['first_name']);
            $last_name  = strval($_SESSION['last_name']);
            $email      = strval($_SESSION['email']);
            $password   = strval($_SESSION['password_hash']);

            $user = \unt\objects\User::register($first_name, $last_name, $gender, $email, $password);
            if (!$user)
                throw new APIException('Failed to create entity: internal server error', 120);
        }

        $response['stage'] = $this->nextStage();

        if ($response['stage'] < 5)
            $response['session_id'] = session_id();

        return $callback(new APIResponse($response));
    }
};