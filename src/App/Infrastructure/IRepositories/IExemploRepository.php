<?php

declare(strict_types=1);

namespace Src\App\Infrastructure\IRepositories;

use Src\App\Models\Exemplo;

// TODO: renomeie para o seu domínio (ex: IProdutoRepository, IClienteRepository)
interface IExemploRepository
{
    public function create(Exemplo $exemplo): Exemplo;
    public function update(Exemplo $exemplo): Exemplo;
    public function delete(string $id): bool;
    public function getAll(): array;
    public function getById(string $id): ?Exemplo;
    public function count(): int;
}
