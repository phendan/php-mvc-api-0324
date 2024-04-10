<?php

namespace App\Models;

use App\Helpers\Str;
use App\Models\Database;
use Exception;

class User
{
    private string $id;
    private string $email;
    private string $firstName;
    private string $lastName;
    private string $password;
    private string $createdAt;
    private string $updatedAt;

    public function __construct(private Database $db)
    {
    }

    public function find(string|int $identifier): bool
    {
        $column = is_int($identifier) ? 'id' : 'email';

        $sql = "SELECT * FROM `users` WHERE {$column} = :identifier";
        $userQuery = $this->db->query($sql, [ 'identifier' => $identifier ]);

        if (!$userQuery->count()) {
            return false;
        }

        $userData = $userQuery->results()[0];

        foreach ($userData as $column => $value) {
            $column = Str::toCamelCase($column);
            $this->{$column} = $value;
        }

        return true;
    }

    public function register(string $email, string $firstName, string $lastName, string $password): void
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT, [ 'cost' => 10 ]);

        $sql = "
            INSERT INTO `users`
            (`email`, `first_name`, `last_name`, `password`, `created_at`, `updated_at`)
            VALUES (:email, :firstName, :lastName, :password, :createdAt, :updatedAt)
        ";

        $this->db->query($sql, [
            'email' => $email,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'password' => $passwordHash,
            'createdAt' => time(),
            'updatedAt' => time()
        ]);
    }

    public function login(string $email, string $password)
    {
        // Versuchen User zu finden
        if (!$this->find($email)) {
            throw new Exception('The email was not found.');
        }

        // Passwörter abgleichen
        if (!password_verify($password, $this->password)) {
            throw new Exception('The passwords did not match.');
        }

        // User einloggen
        $_SESSION['userId'] = (int) $this->id;
    }
}
