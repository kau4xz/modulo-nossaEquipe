<?php

declare(strict_types=1);

namespace Src\App\Http\Middleware;

use Src\App\Services\IServices\IAuthService;
use Src\App\Utils\Url;

class AdminMiddleware implements IMiddleware
{
    private IAuthService $authService;

    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
    }
    public function handle(): void
    {
        if (! $this->authService->checkAdmin()) {
            Url::redirect('/home');
        }
    }
}
