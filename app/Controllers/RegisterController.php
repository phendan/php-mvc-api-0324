<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Request;
use App\Models\FormValidation;
use App\Models\User;

class RegisterController extends BaseController {
    public function index()
    {
        $this->response->view('register');
    }

    public function create(Request $request)
    {
        if (!$request->expectsJson()) {
            return $this->response->json(400, [ 'message' => 'Your request was malformed.' ]);
        }

        $input = $request->getInput();

        // Input Validieren
        $validation = new FormValidation($input, $this->db);

        $validation->setRules([
            'email' => 'required|email|available:users',
            'firstName' => 'required|min:2',
            'lastName' => 'required|min:2',
            'password' => 'required|min:6',
            'passwordAgain' => 'required|matches:password'
        ]);

        $validation->validate();

        if ($validation->fails()) {
            return $this->response->json(422, [
                'errors' => $validation->getErrors()
            ]);
        }

        // User in DB speichern
        $user = new User($this->db);

        $user->register($input['email'], $input['firstName'], $input['lastName'], $input['password']);

        $this->response->json(201, []);
    }
}
