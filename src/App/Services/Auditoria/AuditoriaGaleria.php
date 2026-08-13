<?php

declare(strict_types=1);

namespace Src\App\Services\Auditoria;

use Src\App\Infrastructure\IRepositories\IGaleriaRepository;
use Src\App\Models\Galeria;
use Src\App\Services\IServices\IAuditoriaService;

// TODO: renomeie para o seu domínio (ex: AuditoriaProduto, AuditoriaCliente)
class AuditoriaGaleria implements IGaleriaRepository
{
    public function __construct(
        private IGaleriaRepository $repository,
        private IAuditoriaService $auditoriaService
    ) {
    }

    public function create(Galeria $galeria): Galeria
    {
        $criado = $this->repository->create($galeria);
        $this->auditoriaService->registrar(
            $_SESSION['user_id'] ?? null,
            'Galeria',
            'create',
            $criado->getId(),
            ['titulo' => $criado->getTitulo(), 'legenda' => $criado->getLegenda(), 'tipo' => $criado->getTipo(), 'caminho' => $criado->getCaminho()]
        );
        return $criado;
    }

    public function update(Galeria $galeria): Galeria
    {
        $antes = $this->repository->getById($galeria->getId());
        $atualizado = $this->repository->update($galeria);
        $this->auditoriaService->registrar(
            $_SESSION['user_id'] ?? null,
            'Exemplo',
            'update',
            $atualizado->getId(),
            [
                'antes'  => ['titulo' => $antes?->getTitulo(), 'descricao' => $antes?->getLegenda(), 'tipo' => $antes?->getTipo(), 'caminho' => $antes?->getCaminho()],
                'depois' => ['titulo' => $atualizado->getTitulo(), 'descricao' => $atualizado->getLegenda(), 'tipo' => $atualizado->getTipo(), 'caminho' => $atualizado->getCaminho()],
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

    public function getById(string $id): ?Galeria
    {
        return $this->repository->getById($id);
    }

    public function getByTipo(string $tipo): ?Galeria
    {
        return $this->repository->getByTipo($tipo);
    }

    public function count(): int
    {
        return $this->repository->count();
    }
}