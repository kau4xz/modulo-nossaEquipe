<?php

declare(strict_types=1);

namespace Src\App\Utils;

class Url
{
    private static ?string $basePath = null;

    public static function base(): string
    {
        if (self::$basePath === null) {
            $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
            $basePath = dirname($scriptName);
            $basePath = str_replace('\\', '/', $basePath);
            $basePath = preg_replace('#/(public|swagger)(/.*)?$#', '', $basePath);
            $basePath = rtrim($basePath, '/');
            self::$basePath = $basePath;
        }
        return self::$basePath;
    }

    public static function path(string $path = ''): string
    {
        return self::base() . $path;
    }

    public static function redirect(string $path): never
    {
        header('Location: ' . self::path($path));
        exit;
    }
}
