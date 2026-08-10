# Boas Práticas — Guia para Estagiários

> Leia este documento antes de escrever qualquer código.
> Consulte sempre que tiver dúvida sobre como fazer algo.

---

## 1. Nomenclatura

### Classes
Use **PascalCase** (primeira letra de cada palavra maiúscula):
```php
// ✅ Correto
class GovController {}
class UserRepository {}
class LoginRequest {}

// ❌ Errado
class govController {}
class user_repository {}
class loginrequest {}
```

### Métodos e variáveis
Use **camelCase** (primeira palavra minúscula, demais com maiúscula):
```php
// ✅ Correto
public function buscarTodos(): array {}
$nomeCompleto = 'João Silva';
$totalDeRegistros = 0;

// ❌ Errado
public function BuscarTodos(): array {}
public function buscar_todos(): array {}
$NomeCompleto = 'João Silva';
```

### Interfaces
Sempre comecem com `I` maiúsculo:
```php
// ✅ Correto
interface IUserRepository {}
interface IGovService {}

// ❌ Errado
interface UserRepositoryInterface {}
interface GovServiceContract {}
```

### Métodos de comportamento (não use "set")
Nomeie o que o método **faz**, não o que ele define:
```php
// ✅ Correto — descreve a ação
public function alterarNome(string $nome): void {}
public function atualizaSenha(Senha $senha): void {}
public function definirId(int $id): void {}

// ❌ Errado — getter/setter puro é proibido
public function setNome(string $nome): void {}
public function setId(int $id): void {}
```

### Arquivos e pastas
- Classes PHP: **PascalCase** → `LoginController.php`
- Pastas: **PascalCase** → `Controllers/`, `Middleware/`
- Views: **PascalCase** para pastas, **kebab-case** para arquivos → `Login/esqueci-senha.php`
- Rotas e configs: **camelCase** ou **kebab-case** → `web.php`, `governador.php`

---

## 2. Estrutura de Métodos

### Um método, uma responsabilidade
Cada método deve fazer **uma coisa só**. Se precisar de mais de 20 linhas, provavelmente precisa ser quebrado.

```php
// ✅ Correto — cada método tem uma responsabilidade
public function salvar(): void
{
    $request = (new GovRequest($_POST))->redirectOnFail();
    $this->govService->criarGovernador($request->validated(), $_FILES['foto'] ?? null);
    Url::redirect('/governadores');
}

// ❌ Errado — mistura validação, negócio e apresentação
public function salvar(): void
{
    if (empty($_POST['nome'])) { /* ... */ }
    if (strlen($_POST['nome']) < 3) { /* ... */ }
    $sql = "INSERT INTO tb_governadores ...";
    $stmt = $this->pdo->prepare($sql);
    // ... 40 linhas depois
}
```

### Retorne cedo (early return)
Evite `else` desnecessário quando a condição anterior já retorna:

```php
// ✅ Correto — early return
public function buscarPorId(int $id): ?Governadores
{
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return null;
    }

    return new Governadores($row['nome'], ...);
}

// ❌ Errado — else desnecessário
public function buscarPorId(int $id): ?Governadores
{
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return null;
    } else {
        return new Governadores($row['nome'], ...);
    }
}
```

---

## 3. Controllers

### Regras obrigatórias
- Controller **não faz SQL** — delega para o Service
- Controller **não contém regras de negócio** — só orquestra
- Métodos GET retornam `string` (HTML), métodos POST retornam `void` (redireciona)

```php
// ✅ Correto
public function index(): string
{
    $governadores = $this->govService->listarGovernadores();
    $content = View::render('Governadores/index', ['governadores' => $governadores]);
    return parent::getPage('PROJETO - GOVERNADORES', $content, ['showSidebar' => true]);
}

public function salvar(): void
{
    $request = (new GovRequest($_POST))->redirectOnFail();
    $this->govService->criarGovernador($request->validated());
    Url::redirect('/governadores');
}

// ❌ Errado — SQL direto no controller
public function index(): string
{
    $stmt = $this->pdo->query('SELECT * FROM tb_governadores');
    $governadores = $stmt->fetchAll();
    // ...
}
```

### Tratamento de exceções no controller
Use `try/catch` somente para capturar exceções do domínio e redirecionar com mensagem:

```php
public function salvar(): void
{
    try {
        $request = (new GovRequest($_POST))->redirectOnFail();
        $this->govService->criarGovernador($request->validated());
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Governador criado!'];
        Url::redirect('/governadores');
    } catch (UsuarioException $e) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => $e->getMessage()];
        Url::redirect('/governadores/novo');
    }
}
```

---

## 4. Services

### Regras obrigatórias
- Service **não faz SQL** — usa o repository
- Service **não renderiza views** — só retorna dados ou lança exceções
- Programe para a **interface**, nunca para a classe concreta

```php
// ✅ Correto — depende da interface
class GovService implements IGovService
{
    public function __construct(private IGovRepository $govRepository) {}
}

// ❌ Errado — dependência concreta
class GovService implements IGovService
{
    public function __construct(private GovRepository $govRepository) {}
}
```

### Lance exceções em vez de retornar null ou false
```php
// ✅ Correto — exceção com mensagem clara
public function getUserById(int $id): User
{
    $user = $this->repository->getUserById($id);

    if ($user === null) {
        throw UsuarioException::naoEncontrado();
    }

    return $user;
}

// ❌ Errado — retornar null obriga quem chamou a verificar sempre
public function getUserById(int $id): ?User
{
    return $this->repository->getUserById($id); // pode ser null silencioso
}
```

---

## 5. Repositories

### Regras obrigatórias
- Repository **só faz SQL** — nada de lógica de negócio
- Sempre use **prepared statements** com bind — nunca concatene valores em SQL

```php
// ✅ Correto — prepared statement com bind
public function buscarPorEmail(string $email): ?User
{
    $stmt = $this->conn->prepare('SELECT * FROM tb_usuarios WHERE email = :email');
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? $this->mapear($row) : null;
}

// ❌ NUNCA FAÇA ISSO — SQL injection
public function buscarPorEmail(string $email): ?User
{
    $stmt = $this->conn->query("SELECT * FROM tb_usuarios WHERE email = '$email'");
}
```

---

## 6. Validação

### Sempre use Request para validar formulários
```php
// ✅ Correto — sem arquivos
public function salvar(): void
{
    $request = (new GovRequest($_POST))->redirectOnFail();
    $this->govService->criarGovernador($request->validated());
}

// ✅ Correto — com ou sem upload, sempre passe apenas $_POST
// O Request mescla $_FILES automaticamente quando houver arquivos
public function salvar(): void
{
    $request = (new GovRequest($_POST))->redirectOnFail();
    $data = $request->validated();
    // $data['foto'] já é o array de upload validado (se existir)
    $this->govService->criarGovernador($data);
}

// ❌ Errado — arquivo passado fora do Request, sem validação
public function salvar(): void
{
    $request = (new GovRequest($_POST))->redirectOnFail();
    $this->govService->criarGovernador($request->validated(), $_FILES['foto'] ?? null);
}
```

E no Request, declare a regra do arquivo normalmente:
```php
public function rules(): array
{
    return [
        'nome' => ['required', 'string', 'min:3', 'max:100'],
        'foto' => ['nullable', 'fileType:jpg,jpeg,png,webp', 'fileSize:2048'],
    ];
}
```

### Nunca confie em dados do usuário
Dados de `$_POST`, `$_GET` e `$_FILES` devem sempre ser validados antes do uso.
O `required` do `Request` já sanitiza strings com `htmlspecialchars` e `trim`.

---

## 7. Views

### Sempre escape dados ao exibir
O `Request` já sanitiza todos os campos com `htmlspecialchars` antes de salvar no banco.
Mesmo assim, continue usando `htmlspecialchars` na view como segunda camada de proteção:

```php
// ✅ Correto — defesa em profundidade
<td><?php echo htmlspecialchars($user->getNome()); ?></td>

// ❌ Evitar — mesmo que o dado venha sanitizado, não dependa só disso
<td><?php echo $user->getNome(); ?></td>
```

### Sem lógica de negócio na view
A view só exibe. Cálculos e decisões ficam no controller ou service:

```php
// ✅ Correto — view só exibe o que recebeu
<?php foreach ($governadores as $gov) { ?>
    <tr><td><?php echo htmlspecialchars($gov->getNome()); ?></td></tr>
<?php } ?>

// ❌ Errado — SQL na view
<?php
$stmt = $pdo->query('SELECT * FROM tb_governadores');
foreach ($stmt->fetchAll() as $gov) { ?>
    <tr><td><?php echo $gov['nome']; ?></td></tr>
<?php } ?>
```

### Use os helpers disponíveis
```php
// Recuperar valor anterior do formulário
<input name="nome" value="<?= old('nome') ?>">

// Exibir erro de campo
<?php $err = old_error('nome'); ?>
<?php if ($err !== '') { ?>
    <span class="error-message"><?php echo htmlspecialchars($err); ?></span>
<?php } ?>

// Ler e limpar valor da session
<?php $toast = flash('toast'); ?>

// Gerar token CSRF
<?= csrf() ?>
```

### CSRF em todo formulário POST
Todo formulário que envia dados deve ter o token CSRF:
```html
<form method="POST" action="/governadores/novo">
    <?= csrf() ?>
    <!-- campos do formulário -->
</form>
```

---

## 8. Segurança

### Nunca exponha dados sensíveis
```php
// ❌ Nunca exiba senhas, tokens ou dados internos em views ou logs
Logger::error('Senha do usuário: ' . $senha);
echo $_ENV['DB_PASS'];
```

### Use `===` e `!==` sempre
Operadores estritos evitam comparações inesperadas:
```php
// ✅ Correto
if ($status === 'ativo') {}
if ($codigo !== $_SESSION['cod_senha']) {}

// ❌ Evitar — pode causar bugs silenciosos
if ($status == 'ativo') {}   // '0' == false == null em PHP
if ($codigo != $_SESSION['cod_senha']) {}
```

### Não use inline control structures
```php
// ✅ Correto
if ($usuario === null) {
    return false;
}

// ❌ Errado — dificulta leitura e manutenção
if ($usuario === null) return false;
```

---

## 9. Injeção de Dependência

### Sempre receba dependências pelo construtor
```php
// ✅ Correto
class GovController extends SharedController
{
    public function __construct(private IGovService $govService) {}
}

// ❌ Errado — instancia dentro do método
class GovController extends SharedController
{
    public function index(): string
    {
        $service = new GovService(new GovRepository(new Database()));
        // ...
    }
}
```

### Registre no Container corretamente
Todo controller, service e repository deve ser registrado em `src/config/dependencies/`:
```php
// Ordem correta: repository → service → controller
Container::set(IEventoRepository::class, fn () =>
    new EventoRepository(Container::get(Database::class))
);

Container::set(IEventoService::class, fn () =>
    new EventoService(Container::get(IEventoRepository::class))
);

Container::set(EventoController::class, fn () =>
    new EventoController(Container::get(IEventoService::class))
);
```

---

## 10. Rotas

### Uma rota por ação, verbos corretos
| Ação              | Verbo  | URL                     |
|-------------------|--------|-------------------------|
| Listar            | GET    | `/governadores`         |
| Exibir formulário | GET    | `/governadores/novo`    |
| Salvar novo       | POST   | `/governadores/novo`    |
| Exibir edição     | GET    | `/governadores/editar/{id}` |
| Salvar edição     | POST   | `/governadores/editar/{id}` |
| Deletar           | POST   | `/governadores/deletar` |

```php
$router->get('/governadores',             [GovController::class, 'index'],     [AuthMiddleware::class]);
$router->get('/governadores/novo',        [GovController::class, 'viewCriar'], [AuthMiddleware::class]);
$router->post('/governadores/novo',       [GovController::class, 'salvar'],    [AuthMiddleware::class]);
$router->get('/governadores/editar/{id}', [GovController::class, 'viewEditar'],[AuthMiddleware::class]);
$router->post('/governadores/editar/{id}',[GovController::class, 'atualizar'], [AuthMiddleware::class]);
$router->post('/governadores/deletar',    [GovController::class, 'deletar'],   [AuthMiddleware::class]);
```

### Proteja todas as rotas com middleware
```php
// ✅ Correto — rotas protegidas
$router->get('/admin', [AdminController::class, 'index'], [AuthMiddleware::class, AdminMiddleware::class]);

// ❌ Errado — rota sem proteção permite acesso não autorizado
$router->get('/admin', [AdminController::class, 'index']);
```

---

## 11. Mensagens para o Usuário (Toast)

Use o padrão de toast para feedback após ações POST:

```php
// Sucesso
$_SESSION['toast'] = ['type' => 'success', 'message' => 'Governador criado com sucesso!'];

// Erro
$_SESSION['toast'] = ['type' => 'error', 'message' => 'Erro ao salvar.'];

// Múltiplas mensagens de erro
$_SESSION['toast'] = ['type' => 'error', 'message' => ['Campo A inválido.', 'Campo B obrigatório.']];

Url::redirect('/governadores');
```

O toast é lido e exibido automaticamente pelo `SharedController` no próximo carregamento de página.

---

## 12. Exceções

### Use exceções de domínio para erros esperados
```php
// src/App/Http/Exceptions/Usuario/UsuarioException.php
class UsuarioException extends DomainException
{
    public static function naoEncontrado(): self
    {
        return new self('Usuário não encontrado.');
    }

    public static function emailInvalido(string $msg = 'E-mail já cadastrado.'): self
    {
        return new self($msg);
    }
}

// Uso no service
if ($this->repository->getUserByEmail($email) !== null) {
    throw UsuarioException::emailInvalido();
}
```

### Capture no controller, não no service
O service lança, o controller captura e exibe para o usuário:
```php
// Controller
try {
    $this->userService->createUser($nome, $email, $senha);
} catch (UsuarioException $e) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => $e->getMessage()];
    Url::redirect('/admin/novo');
}
```

---

## 13. Commits Git

### Formato da mensagem
```
tipo: descrição curta em português

Exemplos:
feat: adiciona tela de cadastro de eventos
fix: corrige redirecionamento após login
refactor: extrai validação para EventoRequest
style: aplica PSR-12 nos controllers
docs: adiciona documentação de validação
```

| Prefixo    | Quando usar |
|------------|-------------|
| `feat`     | Nova funcionalidade |
| `fix`      | Correção de bug |
| `refactor` | Melhoria sem mudar comportamento |
| `style`    | Formatação, lint (sem lógica) |
| `docs`     | Documentação |
| `chore`    | Configuração, dependências |

### Commits pequenos e frequentes
Prefira vários commits pequenos a um commit enorme no final do dia.
Cada commit deve representar uma mudança coesa e descritível em uma frase.

---

## 14. Checklist antes de abrir PR

- [ ] O código passa no `vendor/bin/phpcs --standard=PSR12 src/`
- [ ] Não há `var_dump`, `dd()`, `print_r` ou `exit()` esquecido no código
- [ ] Todo formulário tem `<?= csrf() ?>`
- [ ] Dados exibidos na view usam `htmlspecialchars()`
- [ ] Rotas novas têm os middlewares corretos
- [ ] Dependências novas estão registradas no Container
- [ ] Nenhuma senha, token ou dado sensível está em logs ou views
- [ ] Validated() é usado em vez de `$_POST` direto após o Request
