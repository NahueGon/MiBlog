<?php

namespace App\Core;

class View
{
    public function view(string $view, array $data = [], string $layout = 'main')
    {
        extract($data);

        ob_start();
        require __DIR__ . '/../Views/' . str_replace('.', '/', $view) . '.php';
        $content = ob_get_clean();

        require __DIR__ . '/../Views/layouts/' . $layout . '.php';
    }
}