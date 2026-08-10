<?php

declare(strict_types=1);

use Src\App\Http\Controllers\ConfigController;
use Src\App\Services\IServices\IUserService;
use Src\Core\Container;

Container::set(ConfigController::class, static function () {
    return new ConfigController(Container::get(IUserService::class));
});
