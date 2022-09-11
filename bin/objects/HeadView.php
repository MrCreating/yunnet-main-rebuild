<?php

namespace unt\objects;

class HeadView extends View
{
    public function __construct(string $path)
    {
        parent::__construct($path);
    }

    public function setLang (string $lang): HeadView
    {
        $this->content = str_replace('$lang$', $lang, $this->content);
        return $this;
    }

    public function setTitle (string $title): HeadView
    {
        $this->content = str_replace('$title$', $title, $this->content);
        return $this;
    }

    public function show(View $view): void
    {
        header("Content-Type: text/html");
        echo str_replace('$content$', $view->getContent(), $this->content);
    }
}