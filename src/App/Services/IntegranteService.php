<?php

declare(strict_types=1);

namespace Src\App\Services;

use Src\App\Exceptions\NossaEquipe\NossaEquipeException;
use Src\App\Infrastructure\IRepositories\IIntegranteRepository;
use Src\App\Models\Integrante;
use Src\App\Services\IServices\IIntegranteService;

class IntegranteService implements IIntegranteService
{
    public function __construct(
        private IIntegranteRepository $repository
    ) {
    }

    public function create(array $data): Integrante
    {
        $agora = date('Y-m-d H:i:s');

        $novo = Integrante::fromArray([
            'titulo' => $data['nome'],
            'descricao' => $data['cargo'] ?? null,
            'status' => true,
            'created_at' => $agora,
            'updated_at' => $agora,
        ]);

        return $this->repository->create($novo);
    }

    public function update(int $id, array $data): Integrante
    {
        $existente = $this->repository->getById($id);

        if ($existente === null) {
            throw NossaEquipeException::naoEncontrado();
        }

        $atualizado = Integrante::fromArray([
            'id' => $id,
            'titulo' => $data['titulo'],
            'descricao' => $data['descricao'] ?? null,
            'status' => $existente->getStatus(),
            'created_at' => $existente->getCreatedAt(),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->repository->update($atualizado);
    }

    public function delete(int $id): bool
    {
        $existente = $this->repository->getById($id);

        if ($existente === null) {
            throw NossaEquipeException::naoEncontrado();
        }

        return $this->repository->delete($id);
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getById(int $id): ?Integrante
    {
        return $this->repository->getById($id);
    }

    public function count(): int
    {
        return $this->repository->count();
    }
}
