<?php

namespace App\Core;

class Router {
    private array $publicRoutes = [
        'home/index',
        'auth/register',
        'auth/login'
    ];

    public function handleRequest() {
        $url = isset($_GET['url']) ? explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)) : [];

        $controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'HomeController';
        $method = $url[1] ?? 'index';

        $controllerClass = "App\\Controllers\\$controllerName";

        $currentRoute = empty($url) ? 'home/index' : strtolower(($url[0] ?? '') . '/' . ($url[1] ?? 'index'));

        if (!$this->isPublic($currentRoute) && !$this->isAuthenticated()) {
            header('Location: /auth/register');
            exit;
        }

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            if (method_exists($controller, $method)) {
                call_user_func_array([$controller, $method], array_slice($url, 2));
                return;
            }
        }

        echo "404 - Página no encontrada";
    }

    private function isAuthenticated(): bool {
        return isset($_SESSION['user_id']);
    }

    private function isPublic(string $route): bool {
        return in_array($route, $this->publicRoutes);
    }
}