<?php

declare(strict_types=1);

namespace Src\App\Http\Controllers;

use Src\App\Http\Exceptions\Noticia\NoticiaException;
use Src\App\Http\Requests\NoticiaRequest;
use Src\App\Services\IServices\INoticiaService;
use Src\App\Utils\Toast;
use Src\App\Utils\Url;
use Src\App\Utils\View;
use Src\Core\Logger;

// TODO: renomeie para o seu domínio e ajuste as rotas/títulos
class NoticiaController extends SharedController
{
    public function __construct(
        private INoticiaService $noticiaService
    ) {
    }

    public function index(): string
    {
        try {
            $itens = $this->noticiaService->getAll();
            $content = View::render('Noticia/index', [
                'itens'      => $itens,
                'voltarUrl'  => Url::path('/home'),
                'urlCriar'   => Url::path('/noticia/criar'),
                'editarUrl'  => Url::path('/noticia/editar'),
                'deletarUrl' => Url::path('/noticia/deletar'),
                'visualizarUrl' => Url::path('/noticia/visualizar'),
            ]);

            return self::getPage('NOTICIA - LISTAGEM', $content, [
                'showSidebar' => true,
                'bodyClass'   => 'noticia-page',
                'activePage'  => 'noticia',
            ]);
        } catch (NoticiaException $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/home');
        }
    }

    public function criar(): string
    {
        $content = View::render('Noticia/criar', [
            'voltarUrl' => Url::path('/noticia'),
            'urlSalvar' => Url::path('/noticia/salvar'),
        ]);

        return self::getPage('NOTICIA - NOVO REGISTRO', $content, [
            'showSidebar' => true,
            'bodyClass'   => 'noticia-page',
            'activePage'  => 'noticia',
        ]);
    }

    public function editar(): string
    {
        $id = trim((string) filter_input(INPUT_GET, 'id'));

        if ($id === '') {
            Toast::error('ID inválido.');
            Url::redirect('/noticia');
        }

        try {
            $item = $this->noticiaService->getById($id);

            if ($item === null) {
                Toast::error('Registro não encontrado.');
                Url::redirect('/noticia');
            }

            $content = View::render('Noticia/editar', [
                'voltarUrl' => Url::path('/noticia'),
                'urlSalvar' => Url::path('/noticia/atualizar'),
                'item'      => $item,
            ]);

            return self::getPage('NOTICIA - EDITAR REGISTRO', $content, [
                'showSidebar' => true,
                'bodyClass'   => 'noticia-page',
                'activePage'  => 'noticia',
            ]);
        } catch (NoticiaException $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/noticia');
        }
    }

    public function salvar(): never
    {
        try {
            $request = (new NoticiaRequest($_POST))->redirectOnFail();
            $validated = $request->validated();
            
            $this->noticiaService->create($validated);

        
            Toast::success('Registro criado com sucesso!');
            Url::redirect('/noticia');
        } catch (NoticiaException $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/noticia');
        }
    }

    public function atualizar(): void
    {
     
        try {
            $request = (new NoticiaRequest($_POST))->redirectOnFail();
            $validated = $request->validated();

            $this->noticiaService->update((string) $validated['id'], $validated);
            Toast::success('Registro atualizado com sucesso!');
            Url::redirect('/noticia');
        } catch (NoticiaException $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/noticia/editar?id=' . ($_POST['id'] ?? ''));
        }
    }

    public function deletar(): void
    {
        $id = trim((string) filter_input(INPUT_POST, 'id'));

        if ($id === '') {
            Toast::error('ID inválido.');
            Url::redirect('/noticia');
        }

        try {
            $this->noticiaService->delete($id);
            Toast::success('Registro deletado com sucesso!');
            Url::redirect('/noticia');
        } catch (NoticiaException $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/noticia');
        }
    }
    public function visualizar(){
        
        $id = trim((string) filter_input(INPUT_GET, 'id'));
        
       

        try {
            $toast = Toast::pull();

            $titulo = "card";
            $item = $this->noticiaService->getById($id);
            $content = View::render('Noticia/visualizar', [
                'item' => $item,
                'voltarUrl' => Url::path('/noticia'),
            ]);

            return self::getPage('PROJETO - ' . "CARD", $content, [
            'showSidebar' => true,
            'activePage' => 'noticia',
        ], $toast);
        } catch (NoticiaException $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/noticia');
        }     
    }

    
}