<?php

namespace App;

class Request {
    private array $pageParams;

    public function getUrl(): string
    {
        if (!isset($_GET['url'])) {
            return '/';
        }

        $url = rtrim($_GET['url'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);

        return $url;
    }

    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function setParams(array $pageParams): void
    {
        $this->pageParams = $pageParams;
    }

    public function getParams(): array
    {
        return $this->pageParams;
    }
}
