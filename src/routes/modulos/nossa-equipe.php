<?php

declare(strict_types=1);

use Src\App\Http\Controllers\NossaEquipeController;
use Src\App\Http\Middleware\AuthMiddleware;


$router->get('/nossa-equipe', [NossaEquipeController::class, 'index'], [AuthMiddleware::class]);
$router->get('/nossa-equipe/criar', [NossaEquipeController::class, 'criar'], [AuthMiddleware::class]);
$router->get('/nossa-equipe/editar', [NossaEquipeController::class, 'editar'], [AuthMiddleware::class]);
$router->post('/nossa-equipe/salvar', [NossaEquipeController::class, 'salvar'], [AuthMiddleware::class]);
$router->post('/nossa-equipe/atualizar', [NossaEquipeController::class, 'atualizar'], [AuthMiddleware::class]);
$router->post('/nossa-equipe/deletar', [NossaEquipeController::class, 'deletar'], [AuthMiddleware::class]);
$router->get('/nossa-equipe/visualizar', [NossaEquipeController::class, 'visualizar'], [AuthMiddleware::class]);
