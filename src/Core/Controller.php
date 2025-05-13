<?php

namespace App\Core;

class Controller
{
    public function view(string $view, array $data = [], string $layout = 'main')
    {
        (new View())->view($view, $data, $layout);
    }
}