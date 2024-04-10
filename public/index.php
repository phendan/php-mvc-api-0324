<?php

// Dump and die
function dd(...$values)
{
    echo '<pre>', var_dump(...$values), '</pre>';
    die();
}

// Constructs valid paths for the current operating system
// (UNIX Systems use '/', Windows uses '\')
function path(string $path)
{
    return str_replace(['/', '\\'],  DIRECTORY_SEPARATOR, $path);
}

require_once path(__DIR__ . '/../vendor/autoload.php');

{
    $path = path(__DIR__ . '/../');
    $file = file_exists($path . '.env') ? '.env' : '.env.example';
    $dotenv = Dotenv\Dotenv::createImmutable($path, $file);
    $dotenv->load();
}

function env(string $key)
{
    return $_ENV[$key];
}

if (env('ENVIRONMENT') === 'dev') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

session_start();

new App\App;
