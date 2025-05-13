<?php

namespace App\Core;

class ValidationMessage
{
    public static function add(string $field, string $message): void
    {
        $_SESSION['validation'][$field][] = $message;
    }

    public static function get(string $field): array
    {
        return $_SESSION['validation'][$field] ?? [];
    }

    public static function has(string $field): bool
    {
        return !empty($_SESSION['validation'][$field] ?? []);
    }

    public static function all(): array
    {
        return $_SESSION['validation'] ?? [];
    }

    public static function clear(): void
    {
        unset($_SESSION['validation']);
    }
}
