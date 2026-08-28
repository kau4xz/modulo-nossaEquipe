<?php

declare(strict_types=1);

namespace Src\App\Services;

use Src\App\Http\Exceptions\Noticia\NoticiaException;
use Src\App\Infrastructure\IRepositories\INoticiaRepository;
use Src\App\Models\Noticia;
use Src\App\Services\IServices\INoticiaService;
use Src\Core\FileService;

class NoticiaService implements INoticiaService
{
    public function __construct(
        private INoticiaRepository $repository
    ) {}

    public function create(array $data): Noticia
    {
        $agora = date('Y-m-d H:i:s');

        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $url = FileService::save($_FILES['imagem'], 'noticia');
        }


        $novo = Noticia::fromArray([
            'titulo' => $data['titulo'],
            'descricao' => $data['descricao'] ?? null,
            'imagem'    => $url ?? null,
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

    $urlImagem = $existente->getImagem();
    
    if ($data['remover_imagem']) {
        if ($urlImagem) {
            FileService::delete($urlImagem);
        }
        $urlImagem = null;
    }

    // Se o usuário enviou uma imagem NOVA
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        if ($urlImagem) {
            FileService::delete($urlImagem);
        }
        $urlImagem = FileService::save($_FILES['imagem'], 'noticia');
    }

    $atualizado = Noticia::fromArray([
        'id'         => $id,
        'titulo'     => $data['titulo'],
        'descricao'  => $data['descricao'] ?? null,
        'imagem'     => $urlImagem, 
        'status'     => $data['status'],
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
        if($existente->getImagem() != null){
            FileService::delete($existente->getImagem());
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