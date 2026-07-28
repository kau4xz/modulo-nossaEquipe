<?php

declare(strict_types=1);

use Src\App\Http\Controllers\AdminController;
use Src\App\Services\IServices\IUserService;
use Src\Core\Container;

Container::set(AdminController::class, static function () {
    return new AdminController(Container::get(IUserService::class));
});
