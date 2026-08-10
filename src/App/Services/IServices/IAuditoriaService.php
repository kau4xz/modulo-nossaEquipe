<?php

declare(strict_types=1);

namespace Src\App\Services\IServices;

interface IAuditoriaService
{
    public function registrar(?string $usuarioId, string $modulo, string $acao, string $id, array $detalhe): void;
    public function getLastRegistros(int $limit): array;
}
