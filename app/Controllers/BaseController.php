<?php

namespace App\Controllers;

use App\Models\Database;
use App\Response;

class BaseController
{
    protected Database $db;
    protected Response $response;

    public function __construct()
    {
        $this->db = new Database;

        $this->response = new Response();
    }
}
