<?php

namespace App\Controllers;

use App\Request;
use App\Controllers\BaseController;

class HomeController extends BaseController {
    public function index(Request $request)
    {
        $this->response->view('home', [ 'test' => 1 ], 200);
    }
}
