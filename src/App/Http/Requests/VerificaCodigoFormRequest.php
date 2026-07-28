<?php

declare(strict_types=1);

namespace Src\App\Http\Requests;

use Src\Core\Request;

class VerificaCodigoFormRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'codigo' => ['required', 'string', 'regex:/^\d{6}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required' => 'O campo código é obrigatório.',
            'codigo.string' => 'O campo código deve ser uma string.',
            'codigo.regex' => 'O campo código deve conter exatamente 6 dígitos.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O campo email deve ser um email válido.',
        ];
    }
}
