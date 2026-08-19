<?php

declare(strict_types=1);

namespace Src\App\Services\Auditoria;

use Src\App\Infrastructure\IRepositories\IExemploRepository;
use Src\App\Models\Exemplo;
use Src\App\Services\IServices\IAuditoriaService;

// TODO: renomeie para o seu domínio (ex: AuditoriaProduto, AuditoriaCliente)
class AuditoriaExemplo implements IExemploRepository
{
    public function __construct(
        private IExemploRepository $repository,
        private IAuditoriaService $auditoriaService
    ) {
    }

    public function create(Exemplo $exemplo): Exemplo
    {
        $criado = $this->repository->create($exemplo);
        $this->auditoriaService->registrar(
            $_SESSION['user_id'] ?? null,
            'Exemplo',
            'create',
            $criado->getId(),
            ['titulo' => $criado->getTitulo(), 'descricao' => $criado->getDescricao()]
        );
        return $criado;
    }

    public function update(Exemplo $exemplo): Exemplo
    {
        $antes = $this->repository->getById($exemplo->getId());
        $atualizado = $this->repository->update($exemplo);
        $this->auditoriaService->registrar(
            $_SESSION['user_id'] ?? null,
            'Exemplo',
            'update',
            $atualizado->getId(),
            [
                'antes'  => ['titulo' => $antes?->getTitulo(), 'descricao' => $antes?->getDescricao()],
                'depois' => ['titulo' => $atualizado->getTitulo(), 'descricao' => $atualizado->getDescricao()],
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
                'Exemplo',
                'delete',
                $id,
                ['id' => 'Exemplo com id ' . $id . ' deletado']
            );
        }
        return $deleted;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getById(string $id): ?Exemplo
    {
        return $this->repository->getById($id);
    }

    public function count(): int
    {
        return $this->repository->count();
    }
}
