<?php

declare(strict_types=1);

namespace Src\Core;

use Src\App\Models\User;
use Src\App\Utils\Url;

class Auth
{
    public static function attempt(User $user): void
    {
        $_SESSION['usuario'] = $user->getNome();
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['usuario_role'] = $user->getPerfil()->value;
    }

    public static function logout(): void
    {
        session_destroy();
        Url::redirect('/');
    }

    public static function codigoExpirado(): bool
    {
        if (! isset($_SESSION['cod_senha_hash'])) {
            return true;
        }
        return ! isset($_SESSION['codigo_expira_em']) || $_SESSION['codigo_expira_em'] < time();
    }

    public static function autorizadoMudarSenha(): bool
    {
        $autorizado = $_SESSION['redefinicao_autorizada'] ?? false;
        $emailAutorizado = $_SESSION['email_redefinicao'] ?? '';

        return $autorizado && ! empty($emailAutorizado);
    }

    public static function getEmailAutorizado(): string
    {
        return $_SESSION['email_redefinicao'] ?? '';
    }
    public static function getEmailRecuperacao(): string
    {
        return $_SESSION['email_recuperacao'] ?? '';
    }

    public static function getExpiraCodigo(): int
    {
        return (int) ($_SESSION['codigo_expira_em'] ?? 0);
    }
    public static function autorizarRedefinicao(string $email): void
    {
        $_SESSION['redefinicao_autorizada'] = true;
        $_SESSION['email_redefinicao'] = $email;
    }

    public static function limparAutorizacaoRedefinicao(): void
    {
        unset($_SESSION['redefinicao_autorizada']);
        unset($_SESSION['email_redefinicao']);
    }
}
