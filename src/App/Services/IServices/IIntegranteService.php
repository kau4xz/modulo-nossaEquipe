<?php

declare(strict_types=1);

namespace Src\App\Services\IServices;

use Src\App\Models\Integrante;

interface IIntegranteService
{
    public function create(array $data): Integrante;
    public function update(string $id, array $data): Integrante;
    public function delete(string $id): bool;
    public function getAll(): array;
    public function getById(string $id): ?Integrante;
    public function count(): int;
}
