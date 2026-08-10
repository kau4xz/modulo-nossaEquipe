<?php

declare(strict_types=1);

namespace Src\App\Http\Controllers;

use Src\App\Http\Exceptions\Usuario\UsuarioException;
use Src\App\Http\Requests\AtualizarSenhaFormRequest;
use Src\App\Services\IServices\IUserService;
use Src\App\Utils\Toast;
use Src\App\Utils\Url;
use Src\App\Utils\View;
use Src\Core\Logger;

class ConfigController extends SharedController
{
    private IUserService $userService;
    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): string
    {
        if ($_SESSION['usuario'] === null) {
            Url::redirect('/');
        }
        $toast = Toast::pull();

        $content = View::render('Config/index', [
            'usuario' => $this->GetUsuarioLogado(),
            'urlAtualizarSenha' => Url::path('/configuracoes/AtualizarSenha'),
        ]);

        return parent::getPage(
            'PROJETO - Configurações',
            $content,
            [
                'showSidebar' => true,
                'activePage' => 'configuracoes',
            ],
            $toast
        );
    }

    public function atualizarSenha(): void
    {
        try {
            $validated = (new AtualizarSenhaFormRequest($_POST))->redirectOnFail();
            $data = $validated->validated();

            $senhaAtual = $data['senha_atual'];
            $novaSenha = $data['nova_senha'];
            $confirmarSenha = $data['confirmar_senha'];

            if ($novaSenha !== $confirmarSenha) {
                Toast::error('A nova senha e a confirmação não coincidem.');
                Url::redirect('/configuracoes');
            }

            $userId = (string) ($_SESSION['user_id'] ?? '');

            if (! $this->userService->verificarSenhaAtual($userId, $senhaAtual)) {
                Toast::error('Senha atual incorreta.');
                Url::redirect('/configuracoes');
            }

            $this->userService->atualizarSenha($userId, $novaSenha);

            Toast::success('Senha atualizada com sucesso.');
            Url::redirect('/configuracoes');
        } catch (UsuarioException $th) {
            Toast::error($th->getMessage());
            Url::redirect('/configuracoes');
        } catch (\Exception $e) {
            Toast::error('Ocorreu um erro inesperado. Tente novamente.');
            Logger::error('Erro ao atualizar senha: ' . $e->getMessage());
            Url::redirect('/configuracoes');
        }
    }

    private function getUsuarioLogado()
    {
        return $this->userService->GetUserById($_SESSION['user_id']);
    }
}
