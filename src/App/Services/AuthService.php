<?php

declare(strict_types=1);

namespace Src\App\Services;

use Src\App\Enums\Perfil;
use Src\App\Http\Exceptions\Usuario\UsuarioException;
use Src\App\Infrastructure\IRepositories\IUserRepository;
use Src\App\Models\User;
use Src\App\Services\IServices\IAuthService;

class AuthService implements IAuthService
{
    private IUserRepository $repository;

    public function __construct(IUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function auth(string $email, string $senha): User
    {
        $user = $this->repository->getUserByEmail($email);
        if ($user === null || ! $user->verificaSenha($senha) || ! $user->isAtivo()) {
            throw UsuarioException::credenciaisInvalidas();
        }

        return $user;
    }

    public function checkLogin(): bool
    {
        if (! isset($_SESSION['user_id'])) {
            return false;
        }

        $user = $this->repository->getUserById((string) $_SESSION['user_id']);
        if ($user === null || ! $user->isAtivo()) {
            $this->logout();
            return false;
        }

        return true;
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    public function checkAdmin(): bool
    {
        return ($_SESSION['usuario_role'] ?? null) === Perfil::ADMIN->value;
    }
}
