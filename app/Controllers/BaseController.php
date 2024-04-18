<?php

namespace App\Controllers;

use App\Response;
use App\Models\User;
use App\Models\Database;

class BaseController
{
    protected Database $db;
    protected Response $response;
    protected User $user;

    public function __construct()
    {
        $this->db = new Database();

        $this->user = new User($this->db);

        if ($this->user->isSignedIn()) {
            $this->user->find($this->user->getSessionId());
        }

        $this->response = new Response();
    }
}
