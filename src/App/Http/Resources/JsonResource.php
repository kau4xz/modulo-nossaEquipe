<?php

declare(strict_types=1);

namespace Src\App\Http\Resources;

abstract class JsonResource
{
    public function __construct(protected array $data)
    {
    }
    abstract public function toArray(): array;
    public function response(int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => true,
            'data' => $this->toArray(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public static function error(string $message, int $status): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'error' => $message,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
