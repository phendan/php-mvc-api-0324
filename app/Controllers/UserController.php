<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Request;
use App\Models\User;

class UserController extends BaseController
{
    public function index()
    {
        if (!$this->user->isSignedIn()) {
            return $this->response->json(401);
        }

        return $this->response->json(200, [
            'user' => $this->user->getData()
        ]);
    }
}
