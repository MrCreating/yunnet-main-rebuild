<?php

namespace unt\objects;

use unt\exceptions\FileNotFoundException;

class View extends BaseObject
{
    protected string $content;

    protected array $data = [];

    /**
     * @throws FileNotFoundException
     */
    public function __construct(string $path)
    {
        parent::__construct();
        $this->protect();

        if (!file_exists($path))
            throw new FileNotFoundException("View not found.");

        $content = file_get_contents($path);
        $this->content = $content;
    }

    public function getContent (): string
    {
        return $this->content;
    }
}