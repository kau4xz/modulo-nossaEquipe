<?php

declare(strict_types=1);

namespace Src\App\Http\Exceptions\Galeria;

use Src\App\Http\Exceptions\DomainException;

class GaleriaExecptions extends DomainException
{
    public static function erroCriar(): self
    {
        return new self('Erro ao criar registro.');
    }

    public static function erroAtualizar(): self
    {
        return new self('Erro ao atualizar registro.');
    }

    public static function naoEncontrado(): self
    {
        return new self('Registro não encontrado.');
    }

    public static function erroDeletar(): self
    {
        return new self('Erro ao deletar registro.');
    }

    public static function tipoInvalido(): self
    {
        return new self('Tipo de arquivo inválido');
    }

    public static function tamanhoMax(): self
    {
        return new self('Tamanho máximo 10 MB');
    }
}
