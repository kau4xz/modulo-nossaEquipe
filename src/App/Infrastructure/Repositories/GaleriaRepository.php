<?php

declare(strict_types=1);

namespace Src\App\Infrastructure\Repositories;

use PDO;
use Src\App\Infrastructure\IRepositories\IGaleriaRepository;
use Src\App\Models\Galeria;
use Src\Core\Database;

// TODO: renomeie para o seu domínio e adapte os campos SQL
class GaleriaRepository implements IGaleriaRepository
{
    private PDO $conn;

    public function __construct(Database $conn)
    {
        $this->conn = $conn->getConnection();
    }

    public function create(Galeria $galeria): Galeria
    {
        $sql = 'INSERT INTO tb_galeria (id, titulo, legenda, status, created_at, updated_at, tipo, caminho)
                VALUES (:id, :titulo, :legenda, :status, :created_at, :updated_at, :tipo, :caminho)';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $galeria->getId());
        $stmt->bindValue(':titulo', $galeria->getTitulo());
        $stmt->bindValue(':legenda', $galeria->getLegenda());
        $stmt->bindValue(':status', $galeria->getStatus(), PDO::PARAM_BOOL);
        $stmt->bindValue(':created_at', $galeria->getCreatedAt());
        $stmt->bindValue(':updated_at', $galeria->getUpdatedAt());
        $stmt->bindValue(':tipo', $galeria->getTipo());
        $stmt->bindValue(':caminho', $galeria->getCaminho());
        $stmt->execute();

        return $galeria;
    }

    public function update(Galeria $galeria): Galeria
    {
        $sql = 'UPDATE tb_galeria
                SET titulo = :titulo, legenda = :legenda,
                    status = :status, updated_at = :updated_at, tipo = :tipo, caminho = :caminho
                WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':titulo', $galeria->getTitulo());
        $stmt->bindValue(':legenda', $galeria->getLegenda());
        $stmt->bindValue(':status', $galeria->getStatus(), PDO::PARAM_BOOL);
        $stmt->bindValue(':updated_at', $galeria->getUpdatedAt());
        $stmt->bindValue(':id', $galeria->getId(), PDO::PARAM_STR);
        $stmt->bindValue(':tipo', $galeria->getTipo());
        $stmt->bindValue(':caminho', $galeria->getCaminho());
        $stmt->execute();

        return $galeria;
    }

    public function getAll(): array
    {
        $sql = 'SELECT * FROM tb_galeria ORDER BY id DESC';
        $stmt = $this->conn->query($sql);
        $itens = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $itens[] = new Galeria(
                $row['titulo'],
                $row['legenda'],
                (bool) $row['status'],
                $row['created_at'],
                $row['updated_at'],
                $row['id'],   
                $row['tipo'],
                $row['caminho']
            );
        }

        return $itens;
    }

    public function getById(string $id): ?Galeria
    {
        $sql = 'SELECT * FROM tb_galeria WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Galeria(
                $row['titulo'],
                $row['legenda'],
                (bool) $row['status'],
                $row['created_at'],
                $row['updated_at'],
                $row['id'],
                $row['tipo'],
                $row['caminho']
                
            );
        }

        return null;
    }

        public function getByTipo(string $tipo): ?Galeria
    {
        $sql = 'SELECT * FROM tb_galeria WHERE tipo = :tipo';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':tipo', $tipo);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Galeria(
                $row['titulo'],
                $row['legenda'],
                (bool) $row['status'],
                $row['tipo'],
                $row['created_at'],
                $row['updated_at'],
                $row['caminho'],
                $row['id']
            );
        }

        return null;
    }

    public function delete(string $id): bool
    {
        $sql = 'DELETE FROM tb_galeria WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function count(): int
    {
        $stmt = $this->conn->query('SELECT COUNT(*) FROM tb_galeria');
        return (int) $stmt->fetchColumn();
    }
}
