<?php

declare(strict_types=1);

namespace Src\App\Utils;

class Toast
{
    private const SESSION_KEY = 'toast';

    public static function success(string $message): void
    {
        self::set('success', $message);
    }

    public static function error(string|array $message): void
    {
        self::set('error', $message);
    }

    public static function pull(): array|string
    {
        $toast = $_SESSION[self::SESSION_KEY] ?? '';
        unset($_SESSION[self::SESSION_KEY]);
        return $toast;
    }

    private static function set(string $type, string|array $message): void
    {
        $_SESSION[self::SESSION_KEY] = [
            'type' => $type,
            'message' => $message,
        ];
    }
}
