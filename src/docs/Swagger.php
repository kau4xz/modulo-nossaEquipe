<?php

declare(strict_types=1);

namespace Src\docs;

use OpenApi\Generator;
use Src\App\Utils\Url;

class Swagger
{
    public static function generateJson(): string
    {
        $openApi = (new Generator())->generate([
            __DIR__ . '/../App/Http/Controllers/api',
        ]);

        $spec = json_decode($openApi->toJson(), true);

        $basePath = Url::base();
        $scheme = ! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        $spec['servers'] = [
            [
                'url' => "{$scheme}://{$host}{$basePath}",
            ],
        ];
        return json_encode($spec, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
