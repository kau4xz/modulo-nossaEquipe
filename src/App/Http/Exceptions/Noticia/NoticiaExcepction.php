<?php

declare(strict_types=1);

namespace Src\App\Http\Exceptions\Noticia;

use Src\App\Http\Exceptions\DomainException;

class NoticiaException extends DomainException
{
    public static function erroCriar(): self
    {
        return new self('Erro ao criar notícia.');
    }

    public static function erroAtualizar(): self
    {
        return new self('Erro ao atualizar notícia.');
    }

    public static function naoEncontrado(): self
    {
        return new self('Notícia não encontrado.');
    }

    public static function erroDeletar(): self
    {
        return new self('Erro ao deletar notícia.');
    }
}
