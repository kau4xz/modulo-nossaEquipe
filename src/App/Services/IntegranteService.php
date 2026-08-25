<?php

declare(strict_types=1);

namespace Src\App\Services;

use Src\App\Http\Exceptions\NossaEquipe\NossaEquipeException;
use Src\App\Infrastructure\IRepositories\IIntegranteRepository;
use Src\App\Models\Integrante;
use Src\App\Services\IServices\IIntegranteService;
use Src\Core\FileService;

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
            'nome' => $data['nome'],
            'cargo' => $data['cargo'] ?? null,
            'status' => true,
            'created_at' => $agora,
            'updated_at' => $agora,
            'foto' => $data['foto'] ?? null,

        ]);

        return $this->repository->create($novo);
    }

    public function update(string $id, array $data): Integrante
    {
        $existente = $this->repository->getById($id);

        if ($existente === null) {
            throw NossaEquipeException::naoEncontrado();
        }
        $fotoAntiga = $existente->getFoto();
        $urlFoto = $fotoAntiga;
        $removerFoto = false;

        if (is_array($data['foto'])) {
           
            $urlFoto = FileService::update($data['foto'], 'integrantes', $fotoAntiga);
        } elseif (! empty($data['deletar_foto']) && $fotoAntiga) {
            $urlFoto = null;
            $removerFoto = true;
        }

        $atualizado = Integrante::fromArray([
            'id' => $id,
            'nome' => $data['nome'],
            'cargo' => $data['cargo'] ?? null,
            'status' => (bool) $data['status'],
            'created_at' => $existente->getCreatedAt(),
            'updated_at' => date('Y-m-d H:i:s'),
            'foto' => $urlFoto,
        ]);

        $salvo = $this->repository->update($atualizado);

      
        if ($removerFoto) {
            FileService::delete($fotoAntiga);
        }

        return $salvo;
    }

    public function delete(string $id): bool
    {
        $existente = $this->repository->getById($id);

        if ($existente === null) {
            throw NossaEquipeException::naoEncontrado();
        }

        if ($existente->getFoto() != null) {
            FileService::delete($existente->getFoto());
        }

        return $this->repository->delete($id);
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
