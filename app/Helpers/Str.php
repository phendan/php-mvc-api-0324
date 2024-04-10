<?php

namespace App\Helpers;

class Str {
    public static function toCamelCase(string $subject): string
    {
        $words = explode('_', $subject);
        $words = array_map(fn ($word) => ucfirst($word), $words);
        $subject = lcfirst(implode('', $words));

        return $subject;
    }

    public static function toHeaderCase(string $subject)
    {
        $subject = str_replace('HTTP_', '', $subject);
        $subject = strtolower($subject);
        $subject = ucwords($subject, '_');
        $subject = str_replace('_', '-', $subject);

        return $subject;
    }
}
