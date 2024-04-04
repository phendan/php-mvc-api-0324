<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class RegisterController extends BaseController {
    public function index()
    {
        $this->response->view('register');
    }

    public function create()
    {
        echo 'hello from register create';
    }
}
