<?php

namespace App\Models;

use App\Helpers\Str;

class Task
{
    private string $id;
    private string $title;
    private string $description;
    private string $status;
    private string $createdAt;
    private string $updatedAt;
    private string $userId;

    public function __construct(private Database $db, ?array $data = [])
    {
        foreach ($data as $column => $value) {
            $column = Str::toCamelCase($column);
            $this->{$column} = $value;
        }
    }

    public function find(string|int $identifier): bool
    {
        $column = is_int($identifier) ? 'id' : 'title';

        $sql = "SELECT * FROM `to_dos` WHERE {$column} = :identifier";
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

    public function update(string $status)
    {
        $sql = "UPDATE `to_dos` SET `status` = :status WHERE `id` = :id";
        $this->db->query($sql, [ 'status' => $status, 'id' => $this->id ]);
        $this->status = $status;
    }

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCreatedAt(): int
    {
        return (int) $this->createdAt;
    }

    public function getUpdatedAt(): int
    {
        return (int) $this->updatedAt;
    }

    public function getUserId(): int
    {
        return (int) $this->userId;
    }

    public function getData(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'status' => $this->getStatus(),
            'createdAt' => $this->getCreatedAt(),
            'updatedAt' => $this->getUpdatedAt(),
            'userId' => $this->getUserId()
        ];
    }
}
