<?php

declare(strict_types=1);

namespace Src\App\Services\IServices;

use Src\App\Models\Exemplo;

interface IExemploService
{
    public function create(string $titulo, ?string $descricao): Exemplo;
    public function update(string $id, string $titulo, ?string $descricao): Exemplo;
    public function delete(string $id): bool;
    public function getAll(): array;
    public function getById(string $id): ?Exemplo;
    public function count(): int;
}
