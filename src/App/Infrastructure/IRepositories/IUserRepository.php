<?php

declare(strict_types=1);

namespace Src\App\Infrastructure\IRepositories;

use Src\App\Models\User;

interface IUserRepository
{
    public function createUser(User $newUser): User;
    public function updateUser(User $user): User;
    public function deleteUser(string $id): bool;
    public function getAllUser(): array;
    public function getUserById(string $id): ?User;
    public function getUserByEmail(string $email): ?User;
    public function getAllUsersPaginado(int $limit, int $start): array;
    public function countUsers(): int;
}
