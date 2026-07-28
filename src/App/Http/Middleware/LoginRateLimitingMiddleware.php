<?php

declare(strict_types=1);

namespace Src\App\Http\Middleware;

use Src\App\Http\Exceptions\Usuario\UsuarioException;
use Src\App\Utils\Url;
use Src\Core\RateLimiting;

class LoginRateLimitingMiddleware implements IMiddleware
{
    private RateLimiting $rateLimiting;

    public function __construct(RateLimiting $rateLimiting)
    {
        $this->rateLimiting = $rateLimiting;
    }
    public function handle(): void
    {
        try {
            $key = RateLimiting::getIp($_POST['email'] ?? null);
            $this->rateLimiting->bloquear($key, 'login');
        } catch (UsuarioException $e) {
            $_SESSION['erro_login'] = $e->getMessage();
            Url::redirect('/');
        }
    }
}
