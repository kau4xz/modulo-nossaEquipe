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

    public function create(array $data): Galeria
    {   
        $agora = date('Y-m-d H:i:s');
        
        $novo = Galeria::fromArray([
            'titulo' => $data['titulo'],
            'legenda' => $data['legenda'] ?? null,
            'status' => $data['status'],
            'created_at' => $agora,
            'updated_at' => $agora,
            'tipo' => $data['tipo'],
            'caminho' => FileService::save($_FILES['caminho'], 'galeria')
        ]);
        return $this->repository->create($novo);
    }

    public function update(string $id, array $data): Galeria
    {  
        $existente = $this->repository->getById($id);
        
        if ($existente === null) {
            throw GaleriaExecptions::naoEncontrado();
        }

        $atualizado = Galeria::fromArray([
            'id' => $id,
            'titulo' => $data['titulo'],
            'legenda' => $data['legenda'] ?? null,
            'tipo' => $data['tipo'],
            'status' => $data['status'],
            'created_at' => $existente->getCreatedAt(),
            'updated_at' => date('Y-m-d H:i:s'),
            'caminho' => $_FILES['caminho']['name'] === "" ? $existente->getCaminho() : FileService::update($_FILES['caminho'], 'galeria', $existente->getCaminho()) 
        ]);

        return $this->repository->update($atualizado);
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
