<?php

declare(strict_types=1);

namespace Src\App\Services\Auditoria;

use Src\App\Infrastructure\IRepositories\INoticiaRepository;
use Src\App\Models\Noticia;
use Src\App\Services\IServices\IAuditoriaService;

// TODO: renomeie para o seu domínio (ex: AuditoriaProduto, AuditoriaCliente)
class AuditoriaNoticia implements INoticiaRepository
{
    public function __construct(
        private INoticiaRepository $repository,
        private IAuditoriaService $auditoriaService
    ) {
    }

    public function create(Noticia $noticia): Noticia
    {
        $criado = $this->repository->create($noticia);
        $this->auditoriaService->registrar(
            $_SESSION['user_id'] ?? null,
            'Noticia',
            'create',
            $criado->getId(),
            ['titulo' => $criado->getTitulo(), 'descricao' => $criado->getDescricao()]
        );
        return $criado;
    }

    public function update(Noticia $noticia): Noticia
    {
        $antes = $this->repository->getById($noticia->getId());
        $atualizado = $this->repository->update($noticia);
        $this->auditoriaService->registrar(
            $_SESSION['user_id'] ?? null,
            'Noticia',
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
                'Noticia',
                'delete',
                $id,
                ['id' => 'Noticia com id ' . $id . ' deletado']
            );
        }
        return $deleted;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getById(string $id): ?Noticia
    {
        return $this->repository->getById($id);
    }

    public function count(): int
    {
        return $this->repository->count();
    }
}