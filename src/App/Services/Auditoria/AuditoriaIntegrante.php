<?php

declare(strict_types=1);

namespace Src\App\Services\Auditoria;

use Src\App\Infrastructure\IRepositories\IIntegranteRepository;
use Src\App\Models\Integrante;
use Src\App\Services\IServices\IAuditoriaService;

// TODO: renomeie para o seu domínio (ex: AuditoriaProduto, AuditoriaCliente)
class AuditoriaIntegrante implements IintegranteRepository
{
    public function __construct(
        private IIntegranteRepository $repository,
        private IAuditoriaService $auditoriaService
    ) {
    }

    public function create(Integrante $integrante): Integrante
    {
        $criado = $this->repository->create($integrante);
        $this->auditoriaService->registrar(
            $_SESSION['user_id'] ?? null,
            'Integrante',
            'create',
            $criado->getId(),
            ['nome' => $criado->getNome(), 'cargo' => $criado->getCargo()]
        );
        return $criado;
    }

    public function update(Integrante $integrante): Integrante
    {
        $antes = $this->repository->getById($integrante->getId());
        $atualizado = $this->repository->update($integrante);
        $this->auditoriaService->registrar(
            $_SESSION['user_id'] ?? null,
            'Integrante',
            'update',
            $atualizado->getId(),
            [
                'antes'  => ['nome' => $antes?->getNome(), 'cargo' => $antes?->getCargo()],
                'depois' => ['nome' => $atualizado->getNome(), 'cargo' => $atualizado->getCargo()],
            ]
        );
        return $atualizado;
    }

    public function delete(string $id): bool
    {
        $deleted = $this->repository->delete($id);
        if ($deleted) {
            $this->auditoriaService->registrar(
                $_SESSION['user_id'] ?? null,
                'Integrante',
                'delete',
                $id,
                ['id' => 'Integrante com id ' . $id . ' deletado']
            );
        }
        return $deleted;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getById(string $id): ?Integrante
    {
        return $this->repository->getById($id);
    }

    public function count(): int
    {
        return $this->repository->count();
    }
}