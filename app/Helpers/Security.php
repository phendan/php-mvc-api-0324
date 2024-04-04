<?php

namespace App\Helpers;

class Security
{
    public static function sanitizeOutput(string $data)
    {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'utf-8');
    }
}
