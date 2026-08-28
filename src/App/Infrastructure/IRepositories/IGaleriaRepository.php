<?php

declare(strict_types=1);

namespace Src\App\Infrastructure\IRepositories;

use Src\App\Models\Galeria;

// TODO: renomeie para o seu domínio (ex: IProdutoRepository, IClienteRepository)
interface IGaleriaRepository
{
    public function create(Galeria $galeria): Galeria;
    public function update(Galeria $galeria): Galeria;
    public function delete(string $id): bool;
    public function getAll(): array;
    public function getById(string $id): ?Galeria;
    public function getByTipo(string $tipo): ?Galeria;
    public function count(): int;
}
