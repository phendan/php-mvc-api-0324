<?php

namespace App\Controllers;

use App\Response;

class BaseController
{
    protected Response $response;

    public function __construct()
    {
        $this->response = new Response();
    }
}
