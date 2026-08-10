<?php

declare(strict_types=1);

use Src\App\Http\Controllers\ExemploController;
use Src\App\Http\Middleware\AuthMiddleware;

// TODO: renomeie as rotas para o seu domínio (ex: /produtos, /clientes)
$router->get('/exemplo', [ExemploController::class, 'index'], [AuthMiddleware::class]);
$router->get('/exemplo/criar', [ExemploController::class, 'criar'], [AuthMiddleware::class]);
$router->get('/exemplo/editar', [ExemploController::class, 'editar'], [AuthMiddleware::class]);
$router->post('/exemplo/salvar', [ExemploController::class, 'salvar'], [AuthMiddleware::class]);
$router->post('/exemplo/atualizar', [ExemploController::class, 'atualizar'], [AuthMiddleware::class]);
$router->post('/exemplo/deletar', [ExemploController::class, 'deletar'], [AuthMiddleware::class]);
