<?php

declare(strict_types=1);

namespace Src\App\Services;

use Src\App\Infrastructure\IRepositories\IAuditoriaRepository;
use Src\App\Services\IServices\IAuditoriaService;

class AuditoriaService implements IAuditoriaService
{
    private IAuditoriaRepository $auditoriaRepository;

    public function __construct(IAuditoriaRepository $auditoriaRepository)
    {
        $this->auditoriaRepository = $auditoriaRepository;
    }
    public function registrar(?string $usuarioId, string $modulo, string $acao, string $id, array $detalhe): void
    {
        $this->auditoriaRepository->create([
            'usuario_id' => $usuarioId,
            'modulo' => $modulo,
            'acao' => $acao,
            'registro_id' => $id,
            'detalhe' => $detalhe,
        ]);
    }
    public function getLastRegistros(int $limit): array
    {
        return $this->auditoriaRepository->getlastRegistros($limit);
    }
}
