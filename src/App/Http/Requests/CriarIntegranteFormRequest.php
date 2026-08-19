<?php

declare(strict_types=1);

namespace Src\App\Http\Requests;

use Src\Core\Request;

class CriarIntegranteFormRequest extends Request
{
    public function rules(): array
    {
        return [
            'nome'    => ['required', 'string', 'min:3', 'max:150'],
            'cargo' => ['required', 'string', 'max:150'],
            'foto' => ['nullable','fileType:jpg,png,jpeg', 'fileSize:2048']
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome é obrigatório.',
            'nome.string'   => 'O nome deve ser um texto.',
            'nome.min'      => 'O nome deve ter pelo menos 3 caracteres.',
            'nome.max'      => 'O nome não pode exceder 150 caracteres.',

            'cargo.required' => 'O cargo é obrigatório',
            'cargo.string' => 'O cargo deve ser um texto',
            'cargo.max'   => 'O cargo deve ter no máximo 150 caracteres.',

            'foto.fileType' => 'A extensão da foto não foi aceita Extensões aceitas: jpg, png e jpeg.',
            'foto.fileSize' => 'Tamanho máximo de 16MB excedido'
        ];
    }
}
