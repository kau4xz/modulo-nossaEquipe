<?php

declare(strict_types=1);

use Src\App\Http\Controllers\LoginController;
use Src\App\Http\Middleware\GuestMiddleware;
use Src\App\Http\Middleware\LoginRateLimitingMiddleware;

$router->get('/', [LoginController::class, 'index'], [GuestMiddleware::class]);
$router->post(
    '/',
    [LoginController::class, 'autenticar'],
    [LoginRateLimitingMiddleware::class]
);
$router->get('/logout', [LoginController::class, 'logout']);
$router->get(
    '/esqueci-senha',
    [LoginController::class, 'esqueciSenha'],
    [GuestMiddleware::class]
);
$router->post(
    '/esqueci-senha/verificar',
    [LoginController::class, 'verificarEmail'],
    [GuestMiddleware::class]
);
$router->post(
    '/esqueci-senha/redefinir',
    [LoginController::class, 'redefinirSenha'],
    [GuestMiddleware::class]
);
$router->post(
    '/esqueci-senha/verificar-codigo',
    [LoginController::class, 'verificarCodigo'],
    [GuestMiddleware::class]
);
