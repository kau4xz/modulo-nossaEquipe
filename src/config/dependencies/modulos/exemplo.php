<?php

declare(strict_types=1);

use Src\App\Http\Controllers\ExemploController;
use Src\App\Infrastructure\IRepositories\IExemploRepository;
use Src\App\Infrastructure\Repositories\ExemploRepository;
use Src\App\Services\Auditoria\AuditoriaExemplo;
use Src\App\Services\ExemploService;
use Src\App\Services\IServices\IAuditoriaService;
use Src\App\Services\IServices\IExemploService;
use Src\Core\Container;
use Src\Core\Database;


// =====================
// REPOSITORIES
// =====================
Container::set(IExemploRepository::class, static function () {
    return new AuditoriaExemplo(
        new ExemploRepository(Container::get(Database::class)),
        Container::get(IAuditoriaService::class)
    );
});

// =====================
// SERVICES
// =====================
Container::set(IExemploService::class, static function () {
    return new ExemploService(
        Container::get(IExemploRepository::class)
    );
});

// =====================
// CONTROLLERS
// =====================
Container::set(ExemploController::class, static function () {
    return new ExemploController(
        Container::get(IExemploService::class)
    );
});
