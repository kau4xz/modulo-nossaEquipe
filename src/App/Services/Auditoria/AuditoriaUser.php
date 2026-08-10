<?php

declare(strict_types=1);

namespace Src\App\Services\Auditoria;

use Src\App\Infrastructure\IRepositories\IUserRepository;
use Src\App\Models\User;
use Src\App\Services\IServices\IAuditoriaService;

class AuditoriaUser implements IUserRepository
{
    public function __construct(
        private IUserRepository $userRepository,
        private IAuditoriaService $auditoriaService
    ) {
    }

    public function createUser(User $user): User
    {
        $userCriado = $this->userRepository->createUser($user);
        $detalhe = [
            'nome' => $userCriado->getNome(),
            'email' => $userCriado->getEmail(),
            'perfil' => $userCriado->getPerfil()->name,
            'status' => $userCriado->getStatus()->name,
        ];
        $this->auditoriaService->registrar(
            $_SESSION['user_id'] ?? null,
            'User',
            'create',
            $userCriado->getId(),
            $detalhe
        );
        return $userCriado;
    }

    public function updateUser(User $user): User
    {
        $antes = $this->userRepository->getUserById($user->getId());
        $userAtualizado = $this->userRepository->updateUser($user);
        $detalhe = [
            'antes' => [
                'nome' => $antes?->getNome(),
                'email' => $antes?->getEmail(),
                'perfil' => $antes?->getPerfil()->name,
                'status' => $antes?->getStatus()->name,
            ],
            'depois' => [
                'nome' => $userAtualizado->getNome(),
                'email' => $userAtualizado->getEmail(),
                'perfil' => $userAtualizado->getPerfil()->name,
                'status' => $userAtualizado->getStatus()->name,
            ],
        ];
        $this->auditoriaService->registrar(
            $_SESSION['user_id'] ?? $userAtualizado->getId(),
            'User',
            'update',
            $userAtualizado->getId(),
            $detalhe
        );
        return $userAtualizado;
    }

    public function deleteUser(string $id): bool
    {
        $deleted = $this->userRepository->deleteUser($id);
        if ($deleted) {
            $detalhe = ['id' => 'Usuario com id ' . $id . ' deletado'];
            $this->auditoriaService->registrar($_SESSION['user_id'] ?? null, 'User', 'delete', $id, $detalhe);
        }
        return $deleted;
    }

    public function getAllUser(): array
    {
        return $this->userRepository->getAllUser();
    }

    public function getUserById(string $id): ?User
    {
        return $this->userRepository->getUserById($id);
    }

    public function getUserByEmail(string $email): ?User
    {
        return $this->userRepository->getUserByEmail($email);
    }

    public function getAllUsersPaginado(int $limit, int $start): array
    {
        return $this->userRepository->getAllUsersPaginado($limit, $start);
    }

    public function countUsers(): int
    {
        return $this->userRepository->countUsers();
    }
}
