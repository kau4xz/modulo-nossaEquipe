<?php

declare(strict_types=1);

namespace Src\App\Models;

use Ramsey\Uuid\Uuid;

// TODO: renomeie esta classe e seus campos para o seu domínio
class Galeria
{
    private ?string $id;
    private string $titulo;
    private ?string $legenda;
    private ?bool $status;
    private ?string $created_at;
    private ?string $updated_at;
    private ?string $tipo;
    private ?string $caminho;

    public function __construct(
        string $titulo,
        ?string $legenda = null,
        ?bool $status = null,
        ?string $created_at = null,
        ?string $updated_at = null,
        ?string $id = null,
        ?string $tipo = null,
        ?string $caminho = null
    ) {
        $this->setId($id);
        $this->titulo = $titulo;
        $this->legenda = $legenda;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->tipo = $tipo;
        $this->caminho = $caminho;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['titulo'],
            $data['legenda'] ?? null,
            $data['status'] ?? null,
            $data['created_at'] ?? null,
            $data['updated_at'] ?? null,
            $data['id'] ?? Uuid::uuid7()->toString(),
            $data['tipo'] ?? null,
            $data['caminho'] ?? null
        );
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id ?? Uuid::uuid7()->toString();
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }

    public function getLegenda(): ?string
    {
        return $this->legenda;
    }

    public function setLegenda(?string $descricao): void
    {
        $this->legenda = $descricao;
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

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(?string $tipo): void
    {
        $this->tipo = $tipo;
    }

    public function getCaminho(): ?string
    {
        return $this->caminho;
    }

    public function setCaminho(?string $caminho): void
    {
        $this->caminho = $caminho;
    }
}
