<?php

declare(strict_types=1);

use Src\App\Http\Controllers\HomeController;
use Src\App\Http\Middleware\AuthMiddleware;

$router->get(
    '/home',
    [HomeController::class, 'index'],
    [AuthMiddleware::class]
);
