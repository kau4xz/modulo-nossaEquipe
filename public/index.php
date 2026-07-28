<?php

declare(strict_types=1);

use Src\Core\FileService;
use Src\Core\Router;

session_start();

date_default_timezone_set('America/Sao_Paulo');

require __DIR__ . '/../vendor/autoload.php';

$dontEnv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dontEnv->load();

require __DIR__ . '/../src/config/dependencies.php';
$routes = require __DIR__ . '/../src/routes/web.php';

FileService::init(
    basePath: __DIR__ . '/../public/uploads',
    baseUrl: '/uploads'
);

$router = new Router();
$routes($router);
$router->run();
