<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$router = new Router();
$router->handleRequest();