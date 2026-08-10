<?php

declare(strict_types=1);

namespace Src\Core;

use Src\App\Http\Exceptions\Usuario\UsuarioException;

class RateLimiting
{
    private static string $rateFile = __DIR__ . '/../../Limiting/appLimiting.txt';
    private int $maxAttempts = 5;
    private int $maxAttemptsGlobal = 60;
    private static int $blockDuration = 60;

    public static function getIp(?string $email): string
    {
        $ip = $_SERVER['REMOTE_ADDR'];

        if (isset($_SESSION['user_id'])) {
            return $ip . '#' . $_SESSION['user_id'];
        }

        return $ip . '#' . $email;
    }

    public function tentativas(string $key): int
    {
        $this->limparExpirados();

        if (! file_exists(self::$rateFile)) {
            return 0;
        }

        $lines = file(self::$rateFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $count = 0;

        foreach ($lines as $line) {
            if (explode('|', $line)[0] === $key) {
                $count++;
            }
        }

        return $count;
    }

    public function bloquear(string $key, string $page): void
    {
        $maxAttempts = $page === 'login' ? $this->maxAttempts : $this->maxAttemptsGlobal;
        $minutos = (int) ceil(self::$blockDuration / 60);
        $unidade = $minutos === 1 ? 'minuto' : 'minutos';
        if ($this->tentativas($key) >= $maxAttempts) {
            throw new UsuarioException("Muitas tentativas. Tente novamente em {$minutos} {$unidade}");
        }

        self::registerAttempt($key);
    }

    public static function limpar(string $key): void
    {
        if (! file_exists(self::$rateFile)) {
            return;
        }

        $lines = file(self::$rateFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $filteredLines = array_filter($lines, static function ($line) use ($key) {
            return explode('|', $line)[0] !== $key;
        });

        file_put_contents(self::$rateFile, implode(PHP_EOL, $filteredLines) . PHP_EOL);
    }

    private static function registerAttempt(string $key): void
    {
        $dir = dirname(self::$rateFile);
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $line = "{$key}|" . time() . PHP_EOL;

        file_put_contents(self::$rateFile, $line, FILE_APPEND);
    }

    private static function limparExpirados(): void
    {
        if (! file_exists(self::$rateFile)) {
            return;
        }

        $lines = file(
            self::$rateFile,
            FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
        );

        $agora = time();

        $validas = array_filter(
            $lines,
            static fn ($line) => intval(
                explode('|', $line)[1]
            ) > $agora - self::$blockDuration
        );

        file_put_contents(
            self::$rateFile,
            implode(PHP_EOL, $validas) . PHP_EOL
        );
    }
}
