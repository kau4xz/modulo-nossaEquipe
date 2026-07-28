<?php

declare(strict_types=1);

namespace Src\App\Services;

use Src\App\Services\IServices\IEmailService;
use Src\App\Utils\EmailSend;

class EmailService implements IEmailService
{
    private const EXPIRACAO_MINUTOS = 5;

    public function enviarCodigoRecuperacao(string $email): void
    {
        $codigo = (string) random_int(100000, 999999);
        $expiracao = time() + (self::EXPIRACAO_MINUTOS * 60);

        EmailSend::enviarEmail($email, $codigo, (string) self::EXPIRACAO_MINUTOS);

        $_SESSION['cod_senha_hash'] = hash('sha256', $codigo);
        $_SESSION['codigo_expira_em'] = $expiracao;
        $_SESSION['email_recuperacao'] = $email;
    }

    public function verificarCodigo(string $email, string $codigo): bool
    {
        if (! isset($_SESSION['cod_senha_hash'], $_SESSION['codigo_expira_em'], $_SESSION['email_recuperacao'])) {
            return false;
        }

        if ($_SESSION['codigo_expira_em'] < time()) {
            $this->limparCodigo();
            return false;
        }

        if ($_SESSION['email_recuperacao'] !== $email) {
            return false;
        }

        return hash_equals($_SESSION['cod_senha_hash'], hash('sha256', $codigo));
    }

    public function limparCodigo(): void
    {
        unset($_SESSION['cod_senha_hash'], $_SESSION['codigo_expira_em'], $_SESSION['email_recuperacao']);
    }
}
