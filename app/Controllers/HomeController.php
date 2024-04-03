<?php

namespace App\Controllers;

use App\Request;

class HomeController {
    public function index(Request $request)
    {
        echo 'hello from home';
    }
}
