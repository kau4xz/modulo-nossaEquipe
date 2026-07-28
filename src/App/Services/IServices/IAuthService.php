<?php

declare(strict_types=1);

namespace Src\App\Services\IServices;

use Src\App\Models\User;

interface IAuthService
{
    public function auth(string $email, string $senha): User;
    public function checkLogin(): bool;
    public function logout(): void;
    public function checkAdmin(): bool;
}
