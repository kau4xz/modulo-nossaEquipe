<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

date_default_timezone_set('America/Sao_Paulo');

$dotEnv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotEnv->load();

use Src\App\Enums\Perfil;
use Src\App\Enums\Status;
use Src\App\Models\User;
use Src\App\ValueObjects\Senha;
use Src\App\Infrastructure\Repositories\UserRepository;
use Src\Core\Database;

$repository = new UserRepository(new Database());

// ============================================================
// TESTE 1 — CRIAR USUÁRIO
// ============================================================
echo "=== TESTE 1: Criar usuário ===\n";

$novoUsuario = User::criar(
    nome:   'Joao',
    email:  'jvictorsousa2015@gmail.com',
    senha:  Senha::senha('#Senha123'),
    perfil: Perfil::ADMIN
);

$usuarioCriado = $repository->createUser($novoUsuario);

// echo "Usuário criado com ID: {$usuarioCriado->getId()}\n";
// dump($usuarioCriado);

// ============================================================
// TESTE 2 — BUSCAR USUÁRIO CRIADO
// ============================================================
echo "\n=== TESTE 2: Buscar usuário por ID ===\n";

$usuarioBuscado = $repository->getUserById($usuarioCriado->getId());

if ($usuarioBuscado === null) {
    echo "ERRO: usuário não encontrado no banco.\n";
    exit(1);
}

echo "Usuário encontrado:\n";
echo "  Nome:   {$usuarioBuscado->getNome()}\n";
echo "  Email:  {$usuarioBuscado->getEmail()}\n";
echo "  Perfil: {$usuarioBuscado->getPerfil()->name}\n";
echo "  Status: {$usuarioBuscado->getStatus()->name}\n";

// ============================================================
// TESTE 3 — ATUALIZAR USUÁRIO
// ============================================================
// echo "\n=== TESTE 3: Atualizar usuário ===\n";

// $usuarioBuscado->setNome('João');
// $usuarioBuscado->setPerfil(Perfil::ADMIN);
// $usuarioBuscado->setStatus(Status::INATIVO);

// $usuarioAtualizado = User::atualizar($usuarioBuscado);
// $repository->updateUser($usuarioAtualizado);

// echo "Usuário atualizado. Buscando novamente...\n";

// $usuarioConfirmado = $repository->getUserById($usuarioCriado->getId());

// echo "  Nome:   {$usuarioConfirmado->getNome()}\n";
// echo "  Perfil: {$usuarioConfirmado->getPerfil()->name}\n";
// echo "  Status: {$usuarioConfirmado->getStatus()->name}\n";
// echo "  É admin? " . ($usuarioConfirmado->isAdmin() ? 'Sim' : 'Não') . "\n";
// echo "  Está ativo? " . ($usuarioConfirmado->isAtivo() ? 'Sim' : 'Não') . "\n";

// ============================================================
// LIMPEZA — remove o usuário criado no teste
// ============================================================
// $repository->deleteUser($usuarioCriado->getId());
// echo "\nUsuário de teste removido. Testes concluídos.\n";
