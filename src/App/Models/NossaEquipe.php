<?php

namespace Src\App\Models;

use PhpCsFixer\Fixer\LanguageConstruct\NullableTypeDeclarationFixer;

class Integrante {

    // construtor
    public function __construct(
        private string $nome,
        private ?string $cargo = null,
        private ?string $foto = null,
        private ?string $created_at = null,
        private ?string $update_at = null,
        private ?int $id = null,
        private ?bool $status = null
    ){}

    //DEFINIMOS OS GETTERS 

    public function getNome () : string {
        return $this->nome;
    }
    
    public function getCargo(): ?string {
        return $this->cargo;
    }

    public function getFoto(): ?string {
        return $this->foto;
    }

    public function getCreatedAt(): ?string {
        return $this->created_at;
    }

    public function getUpdated_at(): ?string {
        return $this->update_at;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getStatus(): ?bool {
        return $this->status;
    }

    //DEFINIMOS OS SETTERS 

    public function setNome(string $nome): void {
        $this->nome = $nome;
    }

    public function setCargo(?string $cargo): void {
        $this->cargo = $cargo;
    }

    public function setFoto(?string $foto): void {
        $this->foto = $foto;

    }

    public function seCreated_at(?string $created_at): void {
        $this->created_at = $created_at;
    }

    public function setUpdate_at(?string $update_at): void {
        $this->update_at = $update_at;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setStatus(?string $status): void {
        $this->status = $status;
    }
    
}