<?php

namespace App\Core;

class Notifier
{
    private static array $types = ['success', 'error', 'info'];

    public static function add(string $type, string|array $message): void
    {
        if (!in_array($type, self::$types)) {
            throw new \InvalidArgumentException("Tipo inválido: $type");
        }

        if (is_string($message)) {
            $message = ['text' => $message];
        }

        $_SESSION['notifications'][$type][] = $message;
    }

    public static function get(string $type): array
    {
        return $_SESSION['notifications'][$type] ?? [];
    }

    public static function has(string $type): bool
    {
        return !empty($_SESSION['notifications'][$type] ?? []);
    }

    public static function all(): array
    {
        return $_SESSION['notifications'] ?? [];
    }

    public static function clear(): void
    {
        unset($_SESSION['notifications']);
    }
}
