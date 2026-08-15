<?php

declare(strict_types=1);

namespace Src\App\Models;

use Ramsey\Uuid\Uuid;

// TODO: renomeie esta classe e seus campos para o seu domínio
class Integrante
{
    private ?string $id;
    private string $nome;
    private ?string $cargo;
    private ?bool $status;
    private ?string $created_at;
    private ?string $updated_at;
    private ?string $foto;

    public function __construct(
        string $nome,
        ?string $cargo = null,
        ?bool $status = null,
        ?string $created_at = null,
        ?string $updated_at = null,
        ?int $id = null,
        ?string $foto = null
    ) {
        $this->setId($id);
        $this->nome = $nome;
        $this->cargo = $cargo;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->foto = $foto;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['nome'],
            $data['cargo'] ?? null,
            $data['status'] ?? null,
            $data['created_at'] ?? null,
            $data['updated_at'] ?? null,
            $data['id'] ?? null,
            $data['foto'] ?? null
            
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id ?? Uuid::uuid7()->toString();
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function getCargo(): ?string
    {
        return $this->cargo;
    }

    public function setCargo(?string $cargo): void
    {
        $this->cargo = $cargo;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): void
    {
        $this->status = $status;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function setCreatedAt(?string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public function getFoto(): ?string 
    {
        return $this->foto;
    }

    public function setFoto(?string $foto){
        $this->foto = $foto;
    }
}
