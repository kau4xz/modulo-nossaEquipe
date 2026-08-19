<?php

declare(strict_types=1);

use Src\App\Http\Controllers\NossaEquipeController;
use Src\App\Infrastructure\IRepositories\IIntegranteRepository;
use Src\App\Infrastructure\Repositories\IntegranteRepository;
use Src\App\Services\Auditoria\AuditoriaIntegrante;
use Src\App\Services\IntegranteService;
use Src\App\Services\IServices\IAuditoriaService;
use Src\App\Services\IServices\IIntegranteService;
use Src\Core\Container;
use Src\Core\Database;


// =====================
// REPOSITORIES
// =====================
Container::set(IIntegranteRepository::class, static function () {
    return new AuditoriaIntegrante(
        new IntegranteRepository(Container::get(Database::class)),
        Container::get(IAuditoriaService::class)
    );
});

// =====================
// SERVICES
// =====================
Container::set(IIntegranteService::class, static function () {
    return new IntegranteService(
        Container::get(IIntegranteRepository::class)
    );
});

// =====================
// CONTROLLERS
// =====================
Container::set(NossaEquipeController::class, static function () {
    return new NossaEquipeController(
        Container::get(IIntegranteService::class)
    );
});
