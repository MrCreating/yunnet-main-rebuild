<?php

namespace unt\objects;

use unt\lang\Language;

class HeadView extends View
{
    protected string $title;
    protected string $lang;

    protected array $components = [];
    protected array $scripts = [];
    protected array $stylesheets = [];

    public function __construct()
    {
        parent::__construct(__DIR__ . '/../components/headView.php');

        $this->setTitle('');
        $this->setLang(Language::get());
    }

    public function setTitle(string $title): HeadView
    {
        $this->title = $title;
        return $this;
    }

    public function setLang(Language $lang): HeadView
    {
        $this->lang = $lang->id;
        return $this;
    }

    public function addView(View $view): HeadView
    {
        $this->components[] = $view;
        return $this;
    }

    public function addScript(string $url, string $type = 'text/javascript'): HeadView
    {
        $this->scripts[] = '<script src="' . $url . '" type="' . $type . '"></script>';
        return $this;
    }

    public function addStyleSheet(string $url): HeadView
    {
        $this->stylesheets[] = '<link rel="stylesheet" href="' . $url . '">';
        return $this;
    }

    public function build (): void
    {
        $this->setData('title', $this->title);
        $this->setData('lang', $this->lang);
        $this->setData('stylesheets', $this->stylesheets);
        $this->setData('scripts', $this->scripts);
        $this->setData('components', $this->components);

        header("Content-Type: text/html");
        $this->show();
    }
}