<?php

declare(strict_types=1);

use Src\App\Http\Controllers\HomeController;
use Src\App\Services\IServices\IAuditoriaService;
use Src\App\Services\IServices\IExemploService;
use Src\Core\Container;

Container::set(HomeController::class, static function () {
    return new HomeController(
        Container::get(IAuditoriaService::class),
        Container::get(IExemploService::class)
    );
});
