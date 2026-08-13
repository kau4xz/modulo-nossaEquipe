<?php

declare(strict_types=1);

namespace Src\App\Services;

use Src\App\Http\Exceptions\Galeria\GaleriaExecptions;
use Src\App\Infrastructure\IRepositories\IGaleriaRepository;
use Src\App\Models\Galeria;
use Src\App\Services\IServices\IGaleriaService;
use Src\Core\FileService;

class GaleriaService implements IGaleriaService
{
    public function __construct(
        private IGaleriaRepository $repository
    ) {
    }

    public function create(array $data): void
    {
        $agora = date('Y-m-d H:i:s');
        $imagem = $data['caminho'];
        $caminho = FileService::save($imagem, 'galeria');

        $this->repository->create(Galeria::fromArray($data));
    }

    public function update(string $id, array $data): void
    {
        $existente = $this->repository->getById($id);

        if ($existente === null) {
            throw GaleriaExecptions::naoEncontrado();
        }

        $imagem = $data['caminho'];
        FileService::update($imagem, 'produtos', $existente->getCaminho());         


        $this->repository->update(Galeria::fromArray($data));
    }

    public function delete(string $id): bool
    {
        $existente = $this->repository->getById($id);

        if ($existente === null) {
            throw GaleriaExecptions::naoEncontrado();
        }

        FileService::delete($existente->getCaminho());

        return $this->repository->delete($id);
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
