<?php

namespace App;

use App\Request;
use App\Router;

use App\Controllers\{
    HomeController,
    LoginController,
    RegisterController
};

class App {
    public function __construct()
    {
        $request = new Request();
        $router = new Router();

        $this->defineRoutes($router);
        $router->handleRequest($request);
    }

    private function defineRoutes(Router $router)
    {
        $router->get('/', [HomeController::class, 'index']);

        // $router->get('/register', [RegisterController::class, 'index']);
        $router->post('/register', [RegisterController::class, 'create']);
        $router->post('/login', [LoginController::class, 'create']);

        $router->get('/users/:id', [RegisterController::class, 'index']);
        $router->get('/users/friends', [RegisterController::class, 'index']);
    }
}
