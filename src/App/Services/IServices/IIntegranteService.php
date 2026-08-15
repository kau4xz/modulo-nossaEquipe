<?php

declare(strict_types=1);

namespace Src\App\Services\IServices;

use Src\App\Models\Integrante;

interface IIntegranteService
{
    public function create(array $data): Integrante;
    public function update(int $id, array $data): Integrante;
    public function delete(int $id): bool;
    public function getAll(): array;
    public function getById(int $id): ?Integrante;
    public function count(): int;
}
