<?php

declare(strict_types=1);

use Src\App\Http\Controllers\NoticiaController;
use Src\App\Http\Middleware\AuthMiddleware;

// TODO: renomeie as rotas para o seu domínio (ex: /produtos, /clientes)
$router->get('/noticia', [NoticiaController::class, 'index'], [AuthMiddleware::class]);
$router->get('/noticia/criar', [NoticiaController::class, 'criar'], [AuthMiddleware::class]);
$router->get('/noticia/editar', [NoticiaController::class, 'editar'], [AuthMiddleware::class]);
$router->get('/noticia/visualizar', [NoticiaController::class, 'visualizar'], [AuthMiddleware::class]);
// $router->get('/noticia/visualizar/{id}', [NoticiaController::class, 'visualizar'], [AuthMiddleware::class]);
$router->post('/noticia/salvar', [NoticiaController::class, 'salvar'], [AuthMiddleware::class]);
$router->post('/noticia/atualizar', [NoticiaController::class, 'atualizar'], [AuthMiddleware::class]);
$router->post('/noticia/deletar', [NoticiaController::class, 'deletar'], [AuthMiddleware::class]);
