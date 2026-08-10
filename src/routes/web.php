<?php

declare(strict_types=1);

use Src\Core\Csrf;
use Src\Core\Router;

return static function (Router $router): void {
    Csrf::check();
    Csrf::create();

    foreach (glob(__DIR__ . '/modulos/*.php') as $file) {
        require $file;
    }

    foreach (glob(__DIR__ . '/modulos/api/*.php') as $file) {
        require $file;
    }
};
