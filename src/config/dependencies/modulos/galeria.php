<?php

declare(strict_types=1);

use Src\App\Http\Controllers\GaleriaController;
use Src\App\Infrastructure\IRepositories\IGaleriaRepository;
use Src\App\Infrastructure\Repositories\GaleriaRepository;
use Src\App\Services\Auditoria\AuditoriaGaleria;
use Src\App\Services\GaleriaService;
use Src\App\Services\IServices\IAuditoriaService;
use Src\App\Services\IServices\IGaleriaService;
use Src\Core\Container;
use Src\Core\Database;


// =====================
// REPOSITORIES
// =====================
Container::set(IGaleriaRepository::class, static function () {
    return new AuditoriaGaleria(
        new GaleriaRepository(Container::get(Database::class)),
        Container::get(IAuditoriaService::class)
    );
});

// =====================
// SERVICES
// =====================
Container::set(IGaleriaService::class, static function () {
    return new GaleriaService(
        Container::get(IGaleriaRepository::class)
    );
});

// =====================
// CONTROLLERS
// =====================
Container::set(GaleriaController::class, static function () {
    return new GaleriaController(
        Container::get(IGaleriaService::class)
    );
});
