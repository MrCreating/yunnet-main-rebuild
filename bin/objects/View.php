<?php

namespace unt\objects;

use unt\exceptions\FileNotFoundException;

class View extends BaseObject
{
    protected array  $data = [];
    protected string $path = '';

    /**
     * @throws FileNotFoundException
     */
    public function __construct(string $path, array $data = [])
    {
        parent::__construct();
        $this->protect();

        if (!file_exists($path))
            throw new FileNotFoundException("View not found.");

        $this->path = $path;
        $this->data = $data;
    }

    public function getPath (): string
    {
        return $this->path;
    }

    public function setData (string $key, $value): View
    {
        $this->data[$key] = $value;

        return $this;
    }

    public function show (): void
    {
        ob_start();
        extract($this->data, EXTR_SKIP);
        require_once($this->getPath());
        $contents = ob_get_contents();
        ob_end_clean();

        echo $contents;
    }
}