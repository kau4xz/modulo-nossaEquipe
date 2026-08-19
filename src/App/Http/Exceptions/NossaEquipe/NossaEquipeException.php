<?php 

namespace Src\App\Http\Exceptions\NossaEquipe;

use Src\App\Http\Exceptions\DomainException;

class NossaEquipeException extends DomainException {
    public static function naoEncontrado(): self{
        return new self('Integrante não encontrado.');
    }

    public static function erroCriar(): self {
        return new self('Erro ao cadastrar novo Integrante.');
    }

    public static function erroAtualizar():self {
        return new self('Erro ao atualizar integrante.');
    }

    public static function erroDeletar(): self {
        return new self('Erro ao deletar integrante.');
    }
    

    public static function erroEditar(): self {
        return new self('Erro ao editar integrante.');
    }


}