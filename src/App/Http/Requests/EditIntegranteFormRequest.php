<?php

declare(strict_types=1);

namespace Src\App\Http\Requests;

use Src\Core\Request;

class EditIntegranteFormRequest extends Request
{
    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:150', 'min:3'],
            'cargo' => ['required', 'string', 'max:150'],
            'foto' => ['nullable', 'fileType:jpg,jpeg,png', 'fileSize: 2048'],
            'status' => ['required', 'integer', 'in:0,1'],
            'id' => ['required', 'string']
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.string' => 'O campo nome deve ser uma string.',
            'nome.max' => 'O campo nome deve ter no máximo 150 caracteres.',
            'nome.min' => 'O campo nome deve ter no mínimo 3 caracteres.',

            'cargo.required' => 'O campo cargo é obrigatório.',
            'cargo.string' => 'O campo cargo deve ser uma string.',
            'cargo.max' => 'O campo cargo deve ter no máximo 150 caracteres.',  

            'foto.fileType' => 'A extensão da foto não foi aceita Extensões aceitas: jpg, png e jpeg.',
            'foto.fileSize' => 'Tamanho máximo de 16MB excedido',

            'status.required' => 'O campo status é obrigatório.',
            'status.in' => 'O campo status deve ser "ativo" ou "inativo".',

            'id.required' => 'o campo de ID é obrigatório',
            'id.string' => 'o campo de ID deve ser um texto'
        ];
    }
}
