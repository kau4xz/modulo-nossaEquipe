<?php

declare(strict_types=1);

namespace Src\App\Http\Controllers;

use Src\App\Http\Exceptions\NossaEquipe\NossaEquipeException;
use Src\App\Http\Requests\CriarIntegranteFormRequest;
use Src\App\Http\Requests\EditIntegranteFormRequest;
use Src\App\Services\IServices\IIntegranteService;
use Src\App\Utils\Toast;
use Src\App\Utils\Url;
use Src\App\Utils\View;
use Src\Core\Logger;
use Src\Core\FileService;

// TODO: renomeie para o seu domínio e ajuste as rotas/títulos
class NossaEquipeController extends SharedController
{
    public function __construct(
        private IIntegranteService $integranteService
    ) {
    }

    public function index(): string
    {
        try {
            $itens = $this->integranteService->getAll();
            $content = View::render('NossaEquipe/index', [
                'itens'      => $itens,
                'voltarUrl'  => Url::path('/home'),
                'urlCriar'   => Url::path('/nossa-equipe/criar'),
                'editarUrl'  => Url::path('/nossa-equipe/editar'),
                'deletarUrl' => Url::path('/nossa-equipe/deletar'),
                'visualizarUrl' => Url::path('/nossa-equipe/visualizar'),
            ]);

            return self::getPage('NOSSA EQUIPE - LISTAGEM', $content, [
                'showSidebar' => true,
                'bodyClass'   => 'nossa-equipe-page',
                'activePage'  => 'nossa-equipe',
            ]);
        } catch (NossaEquipeException $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/home');
        }
    }

    public function criar(): string
    {
        $content = View::render('NossaEquipe/criar', [
            'voltarUrl' => Url::path('/nossa-equipe'),
            'urlSalvar' => Url::path('/nossa-equipe/salvar'),
        ]);

        return self::getPage('NOSSA EQUIPE - NOVO REGISTRO', $content, [
            'showSidebar' => true,
            'bodyClass'   => 'nossa-equipe-page',
            'activePage'  => 'nossa-equipe',
        ]);
    }

    public function editar(): string
    {
        $id = trim((string) filter_input(INPUT_GET, 'id'));

        if ($id === '') {
            Toast::error('ID inválido.');
            Url::redirect('/nossa-equipe');
        }

        try {
            $item = $this->integranteService->getById($id);

            if ($item === null) {
                Toast::error('Registro não encontrado.');
                Url::redirect('/nossa-equipe');
            }

            $content = View::render('NossaEquipe/editar', [
                'voltarUrl' => Url::path('/nossa-equipe'),
                'urlSalvar' => Url::path('/nossa-equipe/atualizar'),
                'item'      => $item,
            ]);

            return self::getPage('NOSSA EQUIPE - EDITAR REGISTRO', $content, [
                'showSidebar' => true,
                'bodyClass'   => 'nossa-equipe-page',
                'activePage'  => 'nossa-equipe',
            ]);
        } catch (NossaEquipeException $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/noticia');
        }
    }

    public function salvar(): never
    {
        try {
            $request = (new CriarIntegranteFormRequest($_POST))->redirectOnFail();
            $validated = $request->validated();

            if (is_array($validated['foto'])) {
                $validated['foto'] = FileService::save($validated['foto'], 'integrantes');
                ;
            }

            $this->integranteService->create($validated);


            Toast::success('Registro criado com sucesso!');
            Url::redirect('/nossa-equipe');
        } catch (NossaEquipeException $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/nossa-equipe');
        }
    }

    public function atualizar(): void
    {

        try {
            $request = (new EditIntegranteFormRequest($_POST))->redirectOnFail();
            $validated = $request->validated();

            $this->integranteService->update((string) $validated['id'], $validated);
            Toast::success('Registro atualizado com sucesso!');
            Url::redirect('/nossa-equipe');
        } catch (NossaEquipeException $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/nossa-equipe/editar?id=' . ($_POST['id'] ?? ''));
        }
    }

    public function deletar(): void
    {
        $id = trim((string) filter_input(INPUT_POST, 'id'));

        if ($id === '') {
            Toast::error('ID inválido.');
            Url::redirect('/nossa-equipe');
        }

        try {
            $this->integranteService->delete($id);
            Toast::success('Registro deletado com sucesso!');
            Url::redirect('/nossa-equipe');
        } catch (NossaEquipeException $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/nossa-equipe');
        }
    }
    public function visualizar()
    {

        $id = trim((string) filter_input(INPUT_GET, 'id'));



        try {
            $toast = Toast::pull();

            $titulo = "card";
            $item = $this->integranteService->getById($id);
            $content = View::render('NossaEquipe/visualizar', [
                'item' => $item,
                'voltarUrl' => Url::path('/nossa-equipe'),
            ]);

            return self::getPage('Integrante - ' . "CARD", $content, [
            'showSidebar' => true,
            'activePage' => 'nossa-equipe',
            ], $toast);
        } catch (NossaEquipeException $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/nossa-equipe');
        }
    }
}
