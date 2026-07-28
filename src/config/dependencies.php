<?php

declare(strict_types=1);

require __DIR__ . '/dependencies/compartilhado.php';

foreach (glob(__DIR__ . '/dependencies/modulos/*.php') as $file) {
    require $file;
}

//api
foreach (glob(__DIR__ . '/dependencies/modulos/api/*.php') as $file) {
    require $file;
}
