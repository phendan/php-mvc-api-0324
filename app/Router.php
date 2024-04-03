<?php

namespace App;

use App\Request;

class Router {
    private array $routes = [];

    private string $controller;
    private string $method;
    private array $params;

    public function handleRequest(Request $request)
    {
        $requestUrl = $request->getUrl();
        $requestMethod = $request->getMethod();

        $candidateRoutes = $this->routes[$requestMethod];

        // Find the most specific matches first by sorting dynamic routes to the back
        uksort($candidateRoutes, function ($routeUrlA, $routeUrlB) {
            return substr_count($routeUrlA, ':') <=> substr_count($routeUrlB, ':');
        });

        foreach ($candidateRoutes as $routeUrl => $requestHandler) {
            $routeUrlSegments = explode('/', trim($routeUrl, '/'));
            $requestUrlSegments = explode('/', trim($requestUrl, '/'));

            if (count($routeUrlSegments) !== count($requestUrlSegments)) {
                continue;
            }

            $pageParams = [];

            foreach ($routeUrlSegments as $index => $routeUrlSegment) {
                $requestUrlSegment = $requestUrlSegments[$index];

                if (str_starts_with($routeUrlSegment, ':')) {
                    $paramName = trim($routeUrlSegment, ':');
                    $pageParams[$paramName] = $requestUrlSegment;
                    continue 1;
                }

                if ($routeUrlSegment !== $requestUrlSegment) {
                    continue 2;
                }
            }

            // If we reach here, we know the current route was correct
            [$controller, $method] = $requestHandler;

            if (!class_exists($controller) || !method_exists($controller, $method)) {
                trigger_error("Invalid route: $routeUrl => $controller::$method");
                return;
            }

            $this->controller = $controller;
            $this->method = $method;
            $this->params = $pageParams;

            break;
        }

        $this->invokeController($request);
    }

    private function invokeController(Request $request)
    {
        $request->setParams($this->params);

        $controller = new $this->controller();
        $controller->{$this->method}($request);
    }

    public function get(string $route, array $requestHandler)
    {
        $this->routes['GET'][$route] = $requestHandler;
    }

    public function post(string $route, array $requestHandler)
    {
        $this->routes['POST'][$route] = $requestHandler;
    }

    public function put(string $route, array $requestHandler)
    {
        $this->routes['PUT'][$route] = $requestHandler;
    }

    public function patch(string $route, array $requestHandler)
    {
        $this->routes['PATCH'][$route] = $requestHandler;
    }

    public function delete(string $route, array $requestHandler)
    {
        $this->routes['DELETE'][$route] = $requestHandler;
    }
}
