<?php

declare(strict_types=1);

namespace Src\App\Services;

use Src\App\Http\Exceptions\Exemplo\ExemploException;
use Src\App\Infrastructure\IRepositories\IExemploRepository;
use Src\App\Models\Exemplo;
use Src\App\Services\IServices\IExemploService;

class ExemploService implements IExemploService
{
    public function __construct(
        private IExemploRepository $repository
    ) {
    }

    public function create(string $titulo, ?string $descricao): Exemplo
    {
        $agora = date('Y-m-d H:i:s');

        $novo = new Exemplo($titulo, $descricao, true, $agora, $agora);

        return $this->repository->create($novo);
    }

    public function update(string $id, string $titulo, ?string $descricao): Exemplo
    {
        $existente = $this->repository->getById($id);

        if ($existente === null) {
            throw ExemploException::naoEncontrado();
        }

        $atualizado = new Exemplo(
            $titulo,
            $descricao,
            $existente->getStatus(),
            $existente->getCreatedAt(),
            date('Y-m-d H:i:s'),
            $id
        );

        return $this->repository->update($atualizado);
    }

    public function delete(string $id): bool
    {
        $existente = $this->repository->getById($id);

        if ($existente === null) {
            throw ExemploException::naoEncontrado();
        }

        return $this->repository->delete($id);
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
