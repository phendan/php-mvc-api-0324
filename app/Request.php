<?php

namespace App;

use App\Helpers\Security;
use App\Helpers\Str;

class Request
{
    private array $pageParams;
    private array $headers;

    public function __construct()
    {
        $this->parseHeaders();
        $this->handleCors();
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

    private function handleCors(): void
    {
        // Allow requests from any origin
        // header('Access-Control-Allow-Origin: *');

        // Decide if the origin of the current request is one we want to allow
        if (isset($this->headers['Origin']) && in_array($this->headers['Origin'], $this->getAllowedOrigins())) {
            header("Access-Control-Allow-Origin: {$this->headers['Origin']}");
            header('Access-Control-Allow-Credentials: true');
        }

        if ($this->getMethod() === 'OPTIONS') {
            if (isset($this->headers['Access-Control-Request-Method'])) {
                header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            }

            if (isset($this->headers['Access-Control-Request-Headers'])) {
                $requestHeaders = $this->headers['Access-Control-Request-Headers'];
                header("Access-Control-Allow-Headers: {$requestHeaders}");
            }

            exit;
        }
    }

    private function getAllowedOrigins(): array
    {
        return explode(', ', env('CLIENT_URL'));
    }
}
