<?php

declare(strict_types=1);

namespace Src\App\Infrastructure\Repositories;

use PDO;
use Src\App\Infrastructure\IRepositories\INoticiaRepository;
use Src\App\Models\Noticia;
use Src\Core\Database;

// TODO: renomeie para o seu domínio e adapte os campos SQL
class NoticiaRepository implements INoticiaRepository
{
    private PDO $conn;

    public function __construct(Database $conn)
    {
        $this->conn = $conn->getConnection();
    }

    public function create(Noticia $noticia): Noticia
    {
        $sql = 'INSERT INTO tb_noticia (id, titulo, descricao, imagem, status, created_at, updated_at)
                VALUES (:id, :titulo, :descricao, :imagem, :status, :created_at, :updated_at)';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $noticia->getId(), PDO::PARAM_STR);
        $stmt->bindValue(':titulo', $noticia->getTitulo());
        $stmt->bindValue(':descricao', $noticia->getDescricao());
        $stmt->bindValue(':imagem', $noticia->getImagem());
        $stmt->bindValue(':status', $noticia->getStatus(), PDO::PARAM_BOOL);
        $stmt->bindValue(':created_at', $noticia->getCreatedAt());
        $stmt->bindValue(':updated_at', $noticia->getUpdatedAt());
        $stmt->execute();
        return $noticia;
    }

    public function update(Noticia $noticia): Noticia
    {
        $sql = 'UPDATE tb_noticia
                SET titulo = :titulo, descricao = :descricao, imagem = :imagem,
                    status = :status, updated_at = :updated_at
                WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':titulo', $noticia->getTitulo());
        $stmt->bindValue(':descricao', $noticia->getDescricao());
        $stmt->bindValue(':imagem', $noticia->getImagem());
        $stmt->bindValue(':status', $noticia->getStatus(), PDO::PARAM_BOOL);
        $stmt->bindValue(':updated_at', $noticia->getUpdatedAt());
        $stmt->bindValue(':id', $noticia->getId(), PDO::PARAM_STR);
        $stmt->execute();

        return $noticia;
    }

    public function getAll(): array
    {
        $sql = 'SELECT id, titulo, imagem, descricao, status FROM tb_noticia ORDER BY id DESC';
        $stmt = $this->conn->query($sql);
        $itens = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $itens[] = new Noticia(
                $row['titulo'],
                $row['descricao'],
                $row['imagem'],
                (bool) $row['status'],
                null,
                null,
                $row['id']
            );
        }

        return $itens;
    }

    public function getById(string $id): ?Noticia
    {
        $sql = 'SELECT * FROM tb_noticia WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Noticia(
                $row['titulo'],
                $row['descricao'],
                $row['imagem'],
                (bool) $row['status'],
                $row['created_at'],
                $row['updated_at'],
                $row['id']
            );
        }

        return null;
    }

    public function delete(string $id): bool
    {
        $sql = 'DELETE FROM tb_noticia WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function count(): int
    {
        $stmt = $this->conn->query('SELECT COUNT(*) FROM tb_noticia');
        return (int) $stmt->fetchColumn();
    }
}