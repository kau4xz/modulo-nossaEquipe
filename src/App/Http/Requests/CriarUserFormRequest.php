<?php

declare(strict_types=1);

namespace Src\App\Http\Requests;

use Src\Core\Request;

class CriarUserFormRequest extends Request
{
    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:150', 'min:3'],
            'email' => ['required', 'email', 'max:150'],
            'senha' => ['required', 'string', 'min:8', 'max:150'],
            'perfil' => ['required', 'integer', 'in:1,2'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.string' => 'O campo nome deve ser uma string.',
            'nome.max' => 'O campo nome deve ter no máximo 150 caracteres.',
            'nome.min' => 'O campo nome deve ter no mínimo 3 caracteres.',

            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O campo email deve ser um endereço de email válido.',
            'email.max' => 'O campo email deve ter no máximo 150 caracteres.',

            'senha.required' => 'O campo senha é obrigatório.',
            'senha.string' => 'O campo senha deve ser uma string.',
            'senha.min' => 'O campo senha deve ter no mínimo 8 caracteres.',
            'senha.max' => 'O campo senha deve ter no máximo 150 caracteres.',
            'perfil.required' => 'O campo perfil é obrigatório.',
            'perfil.in' => 'O campo perfil deve ser "admin" ou "user".',

        ];
    }
}
