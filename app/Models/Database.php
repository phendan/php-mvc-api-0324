<?php

namespace App\Models;

use PDO;
use PDOStatement;
use PDOException;

final class Database
{
    private PDO $pdo;
    private PDOStatement $statement;

    private string $table;

    public function __construct()
    {
        $connection = 'mysql:host='. env('DB_HOST') . ';';
        $database = 'dbname=' . env('DB_DATABASE') . ';';
        $charset = 'charset=' . env('DB_CHARSET');

        try {
            $this->pdo = new PDO(
                $connection . $database . $charset,
                env('DB_USERNAME'),
                env('DB_PASSWORD'),
                [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]
            );
        } catch (PDOException $e) {
            dd($e->getMessage());
        }
    }

    public function query(string $sql, array $values = [])
    {
        $this->statement = $this->pdo->prepare($sql);
        $this->statement->execute($values);

        return $this;
    }

    public function table(string $table)
    {
        $this->table = $table;

        return $this;
    }

    public function where(string $column, string $operator, string|int $value)
    {
        $this->query(
            "SELECT * FROM {$this->table} WHERE {$column} {$operator} :value",
            [ 'value' => $value ]
        );

        return $this;
    }

    public function count(): int
    {
        return $this->statement->rowCount();
    }

    public function results(): array
    {
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function first(): array
    {
        return $this->results()[0];
    }

    public function last(): array
    {
        return end($this->results());
    }
}
