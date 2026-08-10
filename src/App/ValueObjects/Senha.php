<?php

declare(strict_types=1);

namespace Src\App\ValueObjects;

use Src\App\Http\Exceptions\Usuario\UsuarioException;

class Senha
{
    private const REGRAS = [
        ['regex' => '/.{8,}/',                                  'erro' => 'Mí­nimo de 8 caracteres'],
        ['regex' => '/[!@#$%^&*()_+\-=\[\]{};\':"\\\\|\/?]/',   'erro' => 'Um caracter especial (ex: #@*)'],
        ['regex' => '/[A-Z]/',                                  'erro' => 'Uma letra maiúscula'],
        ['regex' => '/[a-z]/',                                  'erro' => 'Uma letra minúscula'],
        ['regex' => '/[0-9]/',                                  'erro' => 'Um número'],
    ];

    private function __construct(private readonly string $hash)
    {
    }

    public static function senha(string $senha): self
    {
        self::validarRegras($senha);
        return new self(password_hash($senha, PASSWORD_DEFAULT));
    }

    public static function senhaHash(string $hash): self
    {
        return new self($hash);
    }

    public function hash(): string
    {
        return $this->hash;
    }

    public function confere(string $senhaPura): bool
    {
        if (! password_verify($senhaPura, $this->hash)) {
            return false;
        }
        return true;
    }

    private static function validarRegras(string $senha): void
    {
        $erros = [];

        foreach (self::REGRAS as $regra) {
            if (! preg_match($regra['regex'], $senha)) {
                $erros[] = $regra['erro'];
            }
        }

        if (! empty($erros)) {
            throw UsuarioException::senhaInvalida($erros);
        }
    }
}
