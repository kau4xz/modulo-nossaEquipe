<?php

declare(strict_types=1);

namespace Src\App\Enums;

use Src\App\Http\Exceptions\Usuario\UsuarioException;

enum Status: int
{
    case ATIVO = 1;
    case INATIVO = 0;
    

    public function toText(): string
    {
        return match ($this) {
            Status::ATIVO => 'Ativo',
            Status::INATIVO => 'Inativo',
        };
    }

    public static function validar($status): self
    {
        $statusEnum = self::tryFrom($status);

        if ($statusEnum === null) {
            throw UsuarioException::statusInvalido("Status inválido: \"{$status}\".");
        }

        return $statusEnum;
    }
}
