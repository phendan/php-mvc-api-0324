<?php

namespace App;

use App\Helpers\Security;
use App\Helpers\Str;

class Request {
    private array $pageParams;
    private array $headers;

    public function __construct()
    {
        $this->parseHeaders();
    }

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

    public function getInput(string $source = 'json'): array
    {
        $input = match($source) {
            'post' => Security::sanitizeInput($_POST),
            'get' => Security::sanitizeInput($_GET),
            'file' => $_FILES,
            'json' => json_decode(file_get_contents('php://input'), true)
        };

        return $input;
    }

    private function parseHeaders(): void
    {
        $rawHeaders = array_filter(
            $_SERVER,
            fn ($key) => str_starts_with($key, 'HTTP_'),
            ARRAY_FILTER_USE_KEY
        );

        $headers = [];

        foreach ($rawHeaders as $name => $value) {
            $name = Str::toHeaderCase($name);
            $headers[$name] = $value;
        }

        $this->headers = $headers;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function expectsJson(): bool
    {
        return in_array('Accept', array_keys($this->headers)) &&
            str_contains($this->headers['Accept'], 'application/json');
    }
}
