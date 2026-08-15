<?php
declare(strict_types=1);

namespace Src\App\Infrastructure\IRepositories;

use Src\App\Models\Integrante;

interface IIntegranteRepository {
    public function create(Integrante $integrante): Integrante;
    public function update(Integrante $integrante): Integrante;
    public function delete(int $id): bool;
    public function getAll(): array;
    public function getById(int $id): ?Integrante;  
    public function count(): int;
}
