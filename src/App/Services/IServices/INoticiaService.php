<?php

declare(strict_types=1);

namespace Src\App\Services\IServices;

use Src\App\Models\Noticia;

interface INoticiaService
{
    public function create(array $data): Noticia;
    public function update(string $id, array $data): Noticia;
    public function delete(string $id): bool;
    public function getAll(): array;
    public function getById(string $id): ?Noticia;
    public function count(): int;
}
