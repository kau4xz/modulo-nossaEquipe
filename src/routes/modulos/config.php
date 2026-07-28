<?php

declare(strict_types=1);

use Src\App\Http\Controllers\ConfigController;
use Src\App\Http\Middleware\AuthMiddleware;

$router->get('/configuracoes', [ConfigController::class, 'index'], [AuthMiddleware::class]);
$router->post('/configuracoes/AtualizarSenha', [ConfigController::class, 'atualizarSenha'], [AuthMiddleware::class]);
