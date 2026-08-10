<?php

declare(strict_types=1);

namespace Src\App\Infrastructure\IRepositories;

interface IAuditoriaRepository
{
    public function create(array $data): void;
    public function getlastRegistros(int $limit): array;
}
