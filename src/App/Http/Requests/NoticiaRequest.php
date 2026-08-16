<?php

declare(strict_types=1);

namespace Src\App\Http\Requests;

use Src\Core\Request;

// TODO: adapte as regras e mensagens para o seu domínio
class NoticiaRequest extends Request
{
    public function rules(): array
    {
        return [
            'id'        => ['nullable', 'string'],
            'titulo'    => ['required', 'string', 'min:3', 'max:150'],
            'descricao' => ['nullable', 'string', 'max:3000'],
            'imagem'    => ['nullable', 'fileType:jpg,jpeg,png,webp', 'fileSize:2048'],
            'status'    => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'O título é obrigatório.',
            'titulo.string'   => 'O título deve ser um texto.',
            'titulo.min'      => 'O título deve ter pelo menos 3 caracteres.',
            'titulo.max'      => 'O título não pode exceder 150 caracteres.',
            'descricao.max'   => 'A descrição não pode exceder 3000 caracteres.',
            'imagem.fileType' => 'O arquivo não está no formato correto',
            'imagem.fileSize' => 'O tamanho máximo do arquivo é 2 MB',
            'imagem'          => 'Erro. Verifique o tamanho e o tipo da imagem',
            'status.required' => 'Informe o status',
        ];
    }
}
