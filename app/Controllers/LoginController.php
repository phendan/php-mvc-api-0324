<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Request;
use App\Models\FormValidation;
use App\Models\User;
use Exception;

class LoginController extends BaseController {
    public function create(Request $request)
    {
        if (!$request->expectsJson()) {
            return $this->response->json(400, [ 'message' => 'Your request was malformed.' ]);
        }

        $input = $request->getInput();

        // Input Validieren
        $validation = new FormValidation($input, $this->db);

        $validation->setRules([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            return $this->response->json(422, [
                'errors' => $validation->getErrors()
            ]);
        }

        // User in DB speichern
        $user = new User($this->db);

        try {
            $user->login($input['email'], $input['password']);
        } catch (Exception $e) {
            return $this->response->json(422, [
                'errors' => [
                    'login' => $e->getMessage()
                ]
            ]);
        }


        $this->response->json(201, []);
    }
}
