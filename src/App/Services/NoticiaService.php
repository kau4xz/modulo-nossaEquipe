<?php

declare(strict_types=1);

namespace Src\App\Services;

use Src\App\Http\Exceptions\Noticia\NoticiaException;
use Src\App\Infrastructure\IRepositories\INoticiaRepository;
use Src\App\Models\Noticia;
use Src\App\Services\IServices\INoticiaService;

class NoticiaService implements INoticiaService
{
    public function __construct(
        private INoticiaRepository $repository
    ) {
    }

    public function create(array $data): Noticia
    {
        $agora = date('Y-m-d H:i:s');

        $novo = Noticia::fromArray([
            'titulo' => $data['titulo'],
            'descricao' => $data['descricao'] ?? null,
            'status' => true,
            'created_at' => $agora,
            'updated_at' => $agora,
        ]);

        return $this->repository->create($novo);
    }

    public function update(string $id, array $data): Noticia
    {
        $existente = $this->repository->getById($id);

        if ($existente === null) {
            throw NoticiaException::naoEncontrado();
        }

        $atualizado = Noticia::fromArray([
            'id' => $id,
            'titulo' => $data['titulo'],
            'descricao' => $data['descricao'] ?? null,
            'status' => $existente->getStatus(),
            'created_at' => $existente->getCreatedAt(),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->repository->update($atualizado);
    }

    public function delete(string $id): bool
    {
        $existente = $this->repository->getById($id);

        if ($existente === null) {
            throw NoticiaException::naoEncontrado();
        }

        return $this->repository->delete($id);
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
