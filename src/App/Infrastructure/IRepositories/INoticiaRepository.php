<?php

declare(strict_types=1);

namespace Src\App\Infrastructure\IRepositories;

use Src\App\Models\Noticia;

// TODO: renomeie para o seu domínio (ex: IProdutoRepository, IClienteRepository)
interface INoticiaRepository
{
    public function create(Noticia $noticia): Noticia;
    public function update(Noticia $noticia): Noticia;
    public function delete(string $id): bool;
    public function getAll(): array;
    public function getById(string $id): ?Noticia;
    public function count(): int;
}
