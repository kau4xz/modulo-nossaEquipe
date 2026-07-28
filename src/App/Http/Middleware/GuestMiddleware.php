<?php

declare(strict_types=1);

namespace Src\App\Http\Middleware;

use Src\App\Services\IServices\IAuthService;
use Src\App\Utils\Url;

class GuestMiddleware implements IMiddleware
{
    private IAuthService $authService;
    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
    }
    public function handle(): void
    {
        if (! isset($_SESSION['user_id'])) {
            return;
        }
        Url::redirect($this->authService->checkAdmin() ? '/admin' : '/home');
    }
}
