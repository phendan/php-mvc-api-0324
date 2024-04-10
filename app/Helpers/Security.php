<?php

namespace App\Helpers;

class Security
{
    public static function sanitizeInput(array $input): array
    {
        return array_map(function ($element) {
            trim($element);
        }, $input);
    }

    public static function sanitizeOutput(string $data)
    {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'utf-8');
    }
}
