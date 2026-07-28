<?php

declare(strict_types=1);

namespace Src\App\Http\Middleware;

interface IMiddleware
{
    public function handle(): void;
}
