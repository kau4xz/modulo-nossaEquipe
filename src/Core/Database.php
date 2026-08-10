<?php

declare(strict_types=1);

namespace Src\Core;

use PDO;
use RuntimeException;

class Database
{
    private $conexao;

    public function __construct()
    {
        $this->getConnection();
    }
    public function getConnection()
    {
        try {
            if (isset($this->conexao)) {
                return $this->conexao;
            }

            $conn_string = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                $_ENV['DB_HOST'],
                $_ENV['DB_PORT'],
                $_ENV['DB_NAME'],
            );

            $this->conexao = new PDO(
                $conn_string,
                $_ENV['DB_USER'],
                $_ENV['DB_PASS'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (\Throwable $error) {
            Logger::error('Erro ao conectar ao banco de dados: ' . $error->getMessage());
            throw new RuntimeException('Erro ao conectar ao banco de dados');
        }

        return $this->conexao;
    }
}
