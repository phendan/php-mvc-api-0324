<?php

namespace App\Controllers;

use App\Request;
use App\Controllers\BaseController;
use App\Models\Database;

class HomeController extends BaseController {
    public function index(Request $request)
    {
        $this->response->view(path: 'home', statusCode: 200);
    }
}
