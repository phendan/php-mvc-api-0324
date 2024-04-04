<?php

namespace App\Controllers;

use App\Request;
use App\Controllers\BaseController;

class NotFoundController extends BaseController {
    public function index(Request $request)
    {
        $this->response->view('404', [
            'requestUrl' => $request->getUrl()
        ], 404);
    }
}
