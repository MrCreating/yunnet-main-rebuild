<?php

namespace unt\objects;

class User extends Entity
{
    public function __construct(int $id)
    {
        parent::__construct($id);
    }

    /**
     * @param array $fields
     * @return array
     */
    public function toArray(array $fields = []): array
    {
        // TODO: Implement toArray() method.
        return [];
    }

    //////////////////////////////////
    public static function register (string $first_name, string $last_name, int $gender, string $email, string $password_hash): ?User
    {
        $gender = $gender != 0 ? 1 : 0;

        $db = DataBase::get()->getClient();
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $db->beginTransaction();

        try {
            $users_insert_res = $db->prepare("INSERT INTO users.info (first_name, last_name, gender, photo, status, screen_name) VALUES (?, ?, ?, '', '', '')")->execute([$first_name, $last_name, $gender]);

            $res = $db->prepare('SELECT LAST_INSERT_ID() AS id');
            if ($res->execute()) {
                $user_id = (int) $res->fetch(\PDO::FETCH_ASSOC)['id'];
                if ($user_id > 0) {
                    $user_login_insert_res = $db->prepare('INSERT INTO users.data (id, email, password, phone) VALUES (?, ?, ?, 0)')->execute([$user_id, $email, $password_hash]);

                    if ($users_insert_res && $user_login_insert_res) {
                        $db->commit();

                        try {
                            return new User($user_id);
                        } catch (\Exception $e) {
                            return NULL;
                        }
                    } else {
                        $db->rollBack();
                    }
                } else {
                    $db->rollBack();
                }
            }
        } catch (\Exception $e) {
            $db->rollBack();
        }

        return NULL;
    }
}