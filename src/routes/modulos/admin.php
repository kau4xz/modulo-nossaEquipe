<?php

declare(strict_types=1);

use Src\App\Http\Controllers\AdminController;
use Src\App\Http\Middleware\AdminMiddleware;
use Src\App\Http\Middleware\AuthMiddleware;

$router->get(
    '/admin',
    [AdminController::class, 'index'],
    [AuthMiddleware::class, AdminMiddleware::class]
);
$router->get(
    '/admin/novo',
    [AdminController::class, 'editar'],
    [AuthMiddleware::class, AdminMiddleware::class]
);
$router->post(
    '/admin/novo',
    [AdminController::class, 'criar'],
    [AuthMiddleware::class, AdminMiddleware::class]
);
$router->get(
    '/admin/editar/{id}',
    [AdminController::class, 'editar'],
    [AuthMiddleware::class, AdminMiddleware::class]
);
$router->post(
    '/admin/editar/{id}',
    [AdminController::class, 'atualizar'],
    [AuthMiddleware::class, AdminMiddleware::class]
);
$router->post(
    '/admin/deletar',
    [AdminController::class, 'deletar'],
    [AuthMiddleware::class, AdminMiddleware::class]
);
$router->get(
    '/admin/detalhes/{id}',
    [AdminController::class, 'detalhes'],
    [
        AuthMiddleware::class,
        AdminMiddleware::class,
    ]
);
