# Arquitetura do Sistema — Guia para Estagiários

## Visão Geral

O sistema segue uma arquitetura em camadas inspirada no padrão MVC com separação de responsabilidades.
Cada camada tem uma responsabilidade única e se comunica apenas com a camada imediatamente abaixo.

```
Requisição HTTP
      │
      ▼
   Router          → decide qual controller chamar
      │
      ▼
 Middleware        → verifica permissões antes do controller
      │
      ▼
 Controller        → recebe a requisição, chama o service, devolve a view
      │
      ▼
  Service          → contém as regras de negócio
      │
      ▼
 Repository        → acessa o banco de dados
      │
      ▼
   Model           → representa os dados de uma entidade
```

---

## Estrutura de Pastas

```
projeto-base-mvc-php/
│
├── public/                          ← Único diretório acessível pelo navegador
│   ├── index.php                    ← Ponto de entrada de toda requisição
│   ├── css/
│   ├── js/
│   └── images/
│
└── src/
    ├── Core/                        ← Infraestrutura interna (não alterar sem necessidade)
    │   ├── Auth.php                 ← Login/logout via session
    │   ├── Container.php            ← Injeção de dependência
    │   ├── Csrf.php                 ← Proteção contra CSRF
    │   ├── Database.php             ← Conexão com banco de dados
    │   ├── Helpers.php              ← Funções globais (old, old_error, flash, csrf)
    │   ├── Logger.php               ← Registro de erros
    │   ├── Request.php              ← Classe base de validação
    │   ├── Router.php               ← Roteamento de URLs
    │   ├── Storage.php              ← Upload de arquivos
    │   └── Validations.php          ← Regras de validação (trait)
    │
    ├── App/                         ← Código da aplicação (aqui você vai trabalhar)
    │   │
    │   ├── Enums/                   ← Valores fixos do sistema
    │   │   ├── HttpStatus.php       ← Códigos HTTP (200, 404, 500...)
    │   │   ├── Perfil.php           ← Tipos de perfil (ADMIN, USER)
    │   │   └── Status.php           ← Status de usuário (ATIVO, INATIVO)
    │   │
    │   ├── Http/
    │   │   ├── Controllers/         ← Recebem requisição e retornam views
    │   │   │   ├── SharedController.php   ← Controller base (getPage, sidebar...)
    │   │   │   ├── LoginController.php
    │   │   │   ├── AdminController.php
    │   │   │   ├── GovController.php
    │   │   │   ├── HomeController.php
    │   │   │   ├── ConfigController.php
    │   │   │   └── ErrorController.php
    │   │   │
    │   │   ├── Middleware/          ← Verificam permissões antes do controller
    │   │   │   ├── IMiddleware.php        ← Interface (contrato)
    │   │   │   ├── AuthMiddleware.php     ← Exige usuário logado
    │   │   │   ├── AdminMiddleware.php    ← Exige perfil ADMIN
    │   │   │   └── GuestMiddleware.php    ← Redireciona se já estiver logado
    │   │   │
    │   │   ├── Requests/            ← Validação de formulários
    │   │   │   └── LoginRequest.php
    │   │   │
    │   │   └── Exceptions/          ← Exceções da aplicação
    │   │       ├── DomainException.php
    │   │       └── Usuario/
    │   │           └── UsuarioException.php
    │   │
    │   ├── Infrastructure/
    │   │   ├── IRepositories/       ← Contratos (interfaces) dos repositórios
    │   │   │   ├── IUserRepository.php
    │   │   │   └── IGovRepository.php
    │   │   └── Repositories/        ← Acesso real ao banco de dados
    │   │       ├── UserRepository.php
    │   │       └── GovRepository.php
    │   │
    │   ├── Models/                  ← Representam entidades do banco
    │   │   ├── User.php
    │   │   └── Governadores.php
    │   │
    │   ├── Services/                ← Regras de negócio
    │   │   ├── IServices/           ← Contratos (interfaces) dos services
    │   │   │   ├── IAuthService.php
    │   │   │   ├── IUserService.php
    │   │   │   ├── IGovService.php
    │   │   │   └── IAuditoriaService.php
    │   │   ├── AuthService.php
    │   │   ├── UserService.php
    │   │   ├── GovService.php
    │   │   └── NullAuditoriaService.php   ← Implementação vazia (placeholder)
    │   │
    │   ├── Utils/                   ← Utilitários reutilizáveis
    │   │   ├── View.php             ← Renderiza arquivos de view
    │   │   ├── Url.php              ← Geração de URLs e redirecionamento
    │   │   └── EmailSend.php        ← Envio de e-mail
    │   │
    │   └── ValueObjects/            ← Objetos que representam um valor com comportamento
    │       └── Senha.php            ← Hash e verificação de senha
    │
    ├── config/
    │   └── dependencies/            ← Registro de dependências no container
    │       ├── compartilhado.php    ← Database, middlewares
    │       ├── governador.php       ← Tudo relacionado a governadores
    │       └── modulos/
    │           ├── login.php        ← UserRepository, AuthService, LoginController...
    │           ├── admin.php
    │           └── config.php
    │
    ├── routes/
    │   ├── web.php                  ← Carrega todos os arquivos de rota
    │   └── modulos/                 ← Uma rota por módulo
    │       ├── login.php
    │       ├── admin.php
    │       ├── governador.php
    │       ├── home.php
    │       └── config.php
    │
    └── views/                       ← Templates HTML com PHP
        ├── Shared/                  ← Layout base reutilizado por todas as páginas
        │   ├── index.php            ← Estrutura HTML principal (title, sidebar, content)
        │   ├── header.php
        │   ├── footer.php
        │   ├── sidebar.php
        │   └── toast.php            ← Mensagens de sucesso/erro
        ├── Login/
        ├── Admin/
        ├── Governadores/
        ├── Home/
        ├── Config/
        └── Errors/
```

---

## O Ciclo de uma Requisição

Exemplo: usuário acessa `GET /admin`

```
1. public/index.php
   └── carrega .env, autoload, dependências, rotas
   └── $router->run()

2. Router
   └── encontra a rota GET /admin
   └── verifica middlewares: [AuthMiddleware, AdminMiddleware]

3. AuthMiddleware::handle()
   └── verifica se há sessão ativa
   └── se não → redireciona para /

4. AdminMiddleware::handle()
   └── verifica se o usuário é ADMIN
   └── se não → redireciona para /home

5. AdminController::index()
   └── chama $this->userService->getAllUsers()
   └── renderiza View::render('Admin/index', [...])
   └── retorna parent::getPage('PROJETO - ADMIN', $content, [...])

6. View
   └── SharedController monta o layout completo
   └── Router faz echo do HTML retornado
```

---

## Camada por Camada

### Router — `src/Core/Router.php`

Define quais URLs existem e quem as responde.

```php
// src/routes/modulos/admin.php
$router->get('/admin', [AdminController::class, 'index'], [AuthMiddleware::class, AdminMiddleware::class]);
//           ^ URL     ^ Controller + método               ^ Middlewares (executados antes)

$router->post('/admin/novo', [AdminController::class, 'salvar'], [AuthMiddleware::class]);
```

**Parâmetros dinâmicos na URL:**
```php
$router->get('/admin/editar/{id}', [AdminController::class, 'editar'], [AuthMiddleware::class]);
// O método editar() recebe string $id automaticamente
```

---

### Middleware — `src/App/Http/Middleware/`

Executados antes do controller. Se a verificação falhar, redireciona.

| Middleware         | Uso                                      | Quando usar |
|--------------------|------------------------------------------|-------------|
| `AuthMiddleware`   | Exige usuário logado                     | Páginas protegidas |
| `AdminMiddleware`  | Exige perfil ADMIN                       | Área administrativa |
| `GuestMiddleware`  | Redireciona se já estiver logado         | Login, cadastro |

**Como criar um novo middleware:**
```php
// src/App/Http/Middleware/MeuMiddleware.php
class MeuMiddleware implements IMiddleware
{
    public function handle(): void
    {
        if (/* condição não atendida */) {
            Url::redirect('/');
        }
    }
}
```

---

### Controller — `src/App/Http/Controllers/`

Recebe a requisição, chama o service, retorna a view. Não contém regras de negócio.

```php
class GovController extends SharedController
{
    public function __construct(private IGovService $govService) {}

    // Método GET: retorna HTML
    public function index(): string
    {
        $governadores = $this->govService->listarGovernadores();

        $content = View::render('Governadores/index', [
            'governadores' => $governadores,
        ]);

        return parent::getPage('PROJETO - GOVERNADORES', $content, [
            'showSidebar' => true,
            'activePage'  => 'governadores',
        ]);
    }

    // Método POST: processa e redireciona
    public function salvar(): void
    {
        $request = (new GovRequest($_POST))->redirectOnFail();

        $this->govService->criarGovernador($request->validated());
        Url::redirect('/governadores');
    }
}
```

**`SharedController::getPage()`** monta o layout completo (header, sidebar, footer) ao redor do seu conteúdo.

---

### Request — `src/App/Http/Requests/`

Valida o formulário antes de chegar no service. Veja `docs/validacao.md` para referência completa.

```php
// Uso padrão em qualquer controller POST
$request = (new MeuRequest($_POST))->redirectOnFail();
// Se falhar → redireciona para a página anterior com erros na session
// Se passar → continua com os dados validados

$data = $request->validated(); // array com os campos que passaram
```

---

### Service — `src/App/Services/`

Contém as regras de negócio. Nunca acessa o banco diretamente — usa o repository.

```php
class GovService implements IGovService
{
    public function __construct(
        private IGovRepository $govRepository,
        private Storage $storage
    ) {}

    public function criarGovernador(array $dados, ?array $arquivo = null): int|bool
    {
        // regra de negócio: salvar foto e depois o governador
        $foto = null;
        if ($arquivo && $this->storage->validar($arquivo)) {
            $foto = $this->storage->put($arquivo, 'images/governadores');
        }

        $governador = Governadores::fromArray($dados, $foto);
        return $this->govRepository->inserir($governador);
    }
}
```

**Sempre programe para a interface, não para a implementação concreta:**
```php
// ✅ Correto — depende da interface
private IGovRepository $govRepository;

// ❌ Errado — dependência concreta dificulta testes e troca de implementação
private GovRepository $govRepository;
```

---

### Repository — `src/App/Infrastructure/Repositories/`

Único lugar que faz SQL. Recebe e retorna Models.

```php
class GovRepository implements IGovRepository
{
    private PDO $conn;

    public function __construct(Database $conn)
    {
        $this->conn = $conn->getConnection();
    }

    public function buscarTodos(): array
    {
        $stmt = $this->conn->prepare('SELECT * FROM tb_governadores ORDER BY ordem ASC');
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn ($row) => Governadores::fromArray($row), $rows);
    }
}
```

---

### Model — `src/App/Models/`

Representa uma entidade. Só contém dados e comportamentos daquela entidade.

```php
class User
{
    public function __construct(
        private string $nome,
        private string $email,
        private Senha $senha,
        private Perfil $perfil,
        private Status $status,
        // ...
    ) {}

    public function isAdmin(): bool
    {
        return $this->perfil === Perfil::ADMIN;
    }

    public function verificaSenha(string $senha): bool
    {
        return $this->senha->confere($senha);
    }
}
```

---

### View — `src/views/`

Arquivos PHP com HTML. Recebem variáveis do controller via `View::render()`.

```php
// Controller passa variáveis:
View::render('Admin/index', ['users' => $users, 'total' => 42]);

// Na view, as variáveis ficam disponíveis diretamente:
// src/views/Admin/index.php
<?php foreach ($users as $user) { ?>
    <tr>
        <td><?php echo htmlspecialchars($user->getNome()); ?></td>
        <td><?php if ($user->isAdmin()) { ?>Admin<?php } ?></td>
    </tr>
<?php } ?>
```

**Tokens `{{chave}}`** são substituídos por strings simples pelo `View::render()`:
```php
// Controller:
View::render('Login/index', ['formAction' => '/login', 'titulo' => 'Entrar']);

// View:
<form action="{{formAction}}">
<h1>{{titulo}}</h1>
```

---

### Container — `src/Core/Container.php`

Gerencia a criação e o compartilhamento de instâncias. Evita criar a mesma instância múltiplas vezes.

```php
// Registrar: src/config/dependencies/modulos/login.php
Container::set(IUserRepository::class, function () {
    return new UserRepository(Container::get(Database::class));
});

Container::set(IAuthService::class, function () {
    return new AuthService(Container::get(IUserRepository::class));
});

// Usar (feito automaticamente pelo Router):
Container::get(LoginController::class);
```

**Quando adicionar um novo módulo**, você precisa criar o arquivo em `src/config/dependencies/modulos/` e registrar as dependências na ordem:
1. Repository
2. Service
3. Controller

---

### Injeção de Dependência

O sistema usa injeção via construtor. O Container resolve automaticamente.

```
LoginController
    └── depende de IAuthService  →  Container resolve → AuthService
    └── depende de IUserService  →  Container resolve → UserService
                                          └── depende de IUserRepository → UserRepository
                                                              └── depende de Database
```

---

## Como adicionar um novo módulo completo

Exemplo: módulo **Eventos**

### 1. Model
```
src/App/Models/Evento.php
```

### 2. Interface do Repository
```
src/App/Infrastructure/IRepositories/IEventoRepository.php
```

### 3. Repository
```
src/App/Infrastructure/Repositories/EventoRepository.php
```

### 4. Interface do Service
```
src/App/Services/IServices/IEventoService.php
```

### 5. Service
```
src/App/Services/EventoService.php
```

### 6. Request (se houver formulário)
```
src/App/Http/Requests/EventoRequest.php
```

### 7. Controller
```
src/App/Http/Controllers/EventoController.php
```

### 8. Views
```
src/views/Eventos/index.php
src/views/Eventos/criar.php
src/views/Eventos/editar.php
```

### 9. Rotas
```
src/routes/modulos/evento.php
```
```php
$router->get('/eventos', [EventoController::class, 'index'], [AuthMiddleware::class]);
$router->post('/eventos/novo', [EventoController::class, 'salvar'], [AuthMiddleware::class]);
```

### 10. Dependências
```
src/config/dependencies/evento.php
```
```php
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

Depois inclua no `src/config/dependencies.php`:
```php
require __DIR__ . '/dependencies/evento.php';
```

---

## Regras de ouro

1. **Controller não faz SQL** — delega para o Service
2. **Service não faz SQL** — delega para o Repository
3. **Repository só faz SQL** — não contém lógica de negócio
4. **View não chama Service** — recebe os dados prontos do Controller
5. **Programe para interfaces** — use `IUserRepository`, não `UserRepository` nos construtores
6. **Valide no Request** — nunca valide `$_POST` diretamente no Controller
