<?php

declare(strict_types=1);

namespace Src\App\Infrastructure\Repositories;

use PDO;
use Src\App\Infrastructure\IRepositories\IIntegranteRepository;
use Src\App\Models\Integrante;
use Src\Core\Database;

// TODO: renomeie para o seu domínio e adapte os campos SQL
class IntegranteRepository implements IIntegranteRepository
{
    private PDO $conn;

    public function __construct(Database $conn)
    {
        $this->conn = $conn->getConnection();
    }

    public function create(Integrante $integrante): Integrante
    {
        $sql = 'INSERT INTO tb_integrante (id, nome, cargo, status, created_at, updated_at, foto)
                VALUES (:id, :nome, :cargo, :status, :created_at, :updated_at, :foto)';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $integrante->getId(), PDO::PARAM_STR);
        $stmt->bindValue(':nome', $integrante->getNome());
        $stmt->bindValue(':cargo', $integrante->getCargo());
        $stmt->bindValue(':status', $integrante->getStatus(), PDO::PARAM_BOOL);
        $stmt->bindValue(':created_at', $integrante->getCreatedAt());
        $stmt->bindValue(':updated_at', $integrante->getUpdatedAt());
        $stmt->bindValue(':foto', $integrante->getFoto());
        $stmt->execute();

        return $integrante;
    }

    public function update(Integrante $integrante): Integrante
    {
        $sql = 'UPDATE tb_integrante
                SET nome = :nome, cargo = :cargo,
                    status = :status, updated_at = :updated_at, foto = :foto
                WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nome', $integrante->getNome());
        $stmt->bindValue(':cargo', $integrante->getCargo());
        $stmt->bindValue(':status', $integrante->getStatus(), PDO::PARAM_BOOL);
        $stmt->bindValue(':updated_at', $integrante->getUpdatedAt());
        $stmt->bindValue(':foto', $integrante->getFoto());
        $stmt->bindValue(':id', $integrante->getId(), PDO::PARAM_STR);
        $stmt->execute();

        return $integrante;
    }

    public function getAll(): array
    {
        $sql = 'SELECT id, nome, cargo, foto, created_at, updated_at, status FROM tb_integrante ORDER BY nome DESC';
        $stmt = $this->conn->query($sql);
        $itens = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $itens[] = new Integrante(
                $row['nome'],
                $row['cargo'],
                (bool) $row['status'],
                $row['created_at'],
                $row['updated_at'],
                $row['id'],
                $row['foto'],
            );
        }

        return $itens;
    }

    public function getById(string $id): ?Integrante
    {
        $sql = 'SELECT * FROM tb_integrante WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Integrante(
                $row['nome'],
                $row['cargo'],
                (bool) $row['status'],
                $row['created_at'],
                $row['updated_at'],
                $row['id'],
                $row['foto']
            );
        }

        return null;
    }

    public function delete(string $id): bool
    {
        $sql = 'DELETE FROM tb_integrante WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function count(): int
    {
        $stmt = $this->conn->query('SELECT COUNT(*) FROM tb_integrante');
        return (int) $stmt->fetchColumn();
    }

//     public function byNameAsc(string $nome): array{
//         $sql = 'SELECT id, nome, cargo, foto, created_at, updated_at, status FROM tb_integrante ORDER BY nome';
//         $stmt = $this->conn->query($sql);
//         $itens = [];

//         while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//             $itens[] = new Integrante(
//                 $row['nome'],
//                 $row['cargo'],
//                 (bool) $row['status'],
//                 $row['created_at'],
//                 $row['updated_at'],
//                 $row['id'],
//                 $row['foto'],
//             );
//         }

//         return $itens;
//     }
//     public function byNameDesc(string $nome): array
//     {
//         $sql = 'SELECT id, nome, cargo, foto, created_at, updated_at, status FROM tb_integrante ORDER BY nome DESC';
//         $stmt = $this->conn->query($sql);
//         $itens = [];

//         while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//             $itens[] = new Integrante(
//                 $row['nome'],
//                 $row['cargo'],
//                 (bool) $row['status'],
//                 $row['created_at'],
//                 $row['updated_at'],
//                 $row['id'],
//                 $row['foto'],
//             );
//         }

//         return $itens;
//     }
//     public function byCreatedAsc(string $nome): array
//     {
//         $sql = 'SELECT id, nome, cargo, foto, created_at, updated_at, status FROM tb_integrante ORDER BY created_at';
//         $stmt = $this->conn->query($sql);
//         $itens = [];

//         while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//             $itens[] = new Integrante(
//                 $row['nome'],
//                 $row['cargo'],
//                 (bool) $row['status'],
//                 $row['created_at'],
//                 $row['updated_at'],
//                 $row['id'],
//                 $row['foto'],
//             );
//         }

//         return $itens;
//     }
//     public function byCreatedDesc(string $nome): array
//     {
//         $sql = 'SELECT id, nome, cargo, foto, created_at, updated_at, status FROM tb_integrante ORDER BY created_at DESC';
//         $stmt = $this->conn->query($sql);
//         $itens = [];

//         while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//             $itens[] = new Integrante(
//                 $row['nome'],
//                 $row['cargo'],
//                 (bool) $row['status'],
//                 $row['created_at'],
//                 $row['updated_at'],
//                 $row['id'],
//                 $row['foto'],
//             );
//         }

//         return $itens;
//     }
//     public function byUpdatedAsc(string $nome): array
//     {
//         $sql = 'SELECT id, nome, cargo, foto, created_at, updated_at, status FROM tb_integrante ORDER BY updated_at';
//         $stmt = $this->conn->query($sql);
//         $itens = [];

//         while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//             $itens[] = new Integrante(
//                 $row['nome'],
//                 $row['cargo'],
//                 (bool) $row['status'],
//                 $row['created_at'],
//                 $row['updated_at'],
//                 $row['id'],
//                 $row['foto'],
//             );
//         }

//         return $itens;
//     }
//     public function byUpdatedDesc(string $nome): array
//     {
//         $sql = 'SELECT id, nome, cargo, foto, created_at, updated_at, status FROM tb_integrante ORDER BY updated_at desc';
//         $stmt = $this->conn->query($sql);
//         $itens = [];

//         while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//             $itens[] = new Integrante(
//                 $row['nome'],
//                 $row['cargo'],
//                 (bool) $row['status'],
//                 $row['created_at'],
//                 $row['updated_at'],
//                 $row['id'],
//                 $row['foto'],
//             );
//         }

//         return $itens;
//     }
    
// }
}