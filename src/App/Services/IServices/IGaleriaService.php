<?php

declare(strict_types=1);

namespace Src\App\Services\IServices;

use Src\App\Models\Galeria;

interface IGaleriaService
{
    public function create(array $data): void;
    public function update(string $id, array $data): void;
    public function delete(string $id): bool;
    public function getAll(): array;
    public function getById(string $id): ?Galeria;
    public function getByTipo(string $tipo): ?Galeria;
    public function count(): int;
}
