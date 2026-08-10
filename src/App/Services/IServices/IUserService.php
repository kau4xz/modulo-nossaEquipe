<?php

declare(strict_types=1);

namespace Src\App\Services\IServices;

use Src\App\Models\User;

interface IUserService
{
    public function createUser(array $data): User;
    public function deleteUser(string $id): void;
    public function updateUser(string $id, array $data, ?string $usuarioLogadoId = null): User;
    public function atualizarSenha(string $id, string $novaSenha): User;
    public function redefinirSenha(string $email, string $novaSenha): void;
    public function verificarSenhaAtual(string $id, string $senhaAtual): bool;
    public function getUserById(string $id): User;
    public function getUserByEmail(string $email): User;
    public function getAllUsers(): array;
    public function paginacao(): array;
}
