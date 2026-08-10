<?php

declare(strict_types=1);

namespace Src\App\Http\Controllers\api;

use OpenApi\Attributes as OA;
use Src\App\Utils\View;
use Src\docs\Swagger;

#[OA\Info(title: 'Projeto API', version: '1.0.0')]
class DocsApiController
{
    public function json(): string
    {
        ob_clean();
        header('Content-Type: application/json');
        return Swagger::generateJson();
    }

    public function ui(): void
    {
        echo View::render('public/swagger');
        exit;
    }
}
