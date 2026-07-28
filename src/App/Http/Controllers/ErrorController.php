<?php

declare(strict_types=1);

namespace Src\App\Http\Controllers;

use Src\App\Utils\Url;
use Src\App\Utils\View;

class ErrorController extends SharedController
{
    public static function notFound(): string
    {
        $content = View::render('Errors/404', [
            'baseUrl' => Url::path('/'),
        ]);

        return parent::getPage('404 - Pagina nao encontrada', $content, [
            'showSidebar' => false,
            'bodyClass' => 'error-404',
        ]);
    }
}
