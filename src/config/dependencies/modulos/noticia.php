<?php

declare(strict_types=1);

use Src\App\Http\Controllers\NoticiaController;
use Src\App\Infrastructure\IRepositories\INoticiaRepository;
use Src\App\Infrastructure\Repositories\NoticiaRepository;
use Src\App\Services\Auditoria\AuditoriaNoticia;
use Src\App\Services\NoticiaService;
use Src\App\Services\IServices\IAuditoriaService;
use Src\App\Services\IServices\INoticiaService;
use Src\Core\Container;
use Src\Core\Database;


// =====================
// REPOSITORIES
// =====================
Container::set(INoticiaRepository::class, static function () {
    return new AuditoriaNoticia(
        new NoticiaRepository(Container::get(Database::class)),
        Container::get(IAuditoriaService::class)
    );
});

// =====================
// SERVICES
// =====================
Container::set(INoticiaService::class, static function () {
    return new NoticiaService(
        Container::get(INoticiaRepository::class)
    );
});

// =====================
// CONTROLLERS
// =====================
Container::set(NoticiaController::class, static function () {
    return new NoticiaController(
        Container::get(INoticiaService::class)
    );
});
