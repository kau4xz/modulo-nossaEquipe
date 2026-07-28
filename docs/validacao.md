# Sistema de Validação — Guia para Estagiários

## Visão Geral

O sistema usa uma classe base `Request` para validar dados de formulários antes de chegar ao controller.
O fluxo completo é:

```
Formulário HTML → $_POST → Request (validação) → Controller → Service → Repository
```

---

## Arquitetura

### `src/Core/Request.php` — Classe base abstrata

Toda validação herda desta classe. Ela executa as regras automaticamente no construtor.

```
Request
├── rules()      → você define quais campos validar e com quais regras
├── messages()   → você personaliza as mensagens de erro
├── validated()  → retorna só os campos que passaram na validação
├── errors()     → retorna os erros organizados por campo
└── fails()      → true se houve qualquer erro
```

### `src/Core/Validations.php` — Trait com todas as regras disponíveis

Inclusa automaticamente em `Request`. Contém todas as regras prontas para uso.

### `src/App/Http/Requests/` — Onde ficam seus Requests

Cada formulário tem seu próprio Request. Exemplo: `LoginRequest.php`.

---

## Como criar um novo Request

### Passo 1 — Criar o arquivo em `src/App/Http/Requests/`

```php
<?php

namespace Src\App\Http\Requests;

use Src\Core\Request;

class CadastroRequest extends Request
{
    public function rules(): array
    {
        return [
            'nome'  => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email'],
            'senha' => ['required', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome é obrigatório.',
            'nome.min'      => 'O nome deve ter pelo menos 3 caracteres.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email'    => 'Informe um e-mail válido.',
            'senha.required' => 'A senha é obrigatória.',
            'senha.min'      => 'A senha deve ter no mínimo 8 caracteres.',
        ];
    }
}
```

### Passo 2 — Usar no Controller

```php
public function salvar(): void
{
    // redirectOnFail() redireciona automaticamente para a página anterior
    // se houver erros. Caso contrário, retorna o próprio request e continua.
    $request = (new CadastroRequest($_POST))->redirectOnFail();

    $data = $request->validated();
    // $data['nome'], $data['email'], $data['senha'] — já sanitizados

    $this->userService->createUser($data['nome'], $data['email'], $data['senha']);
    Url::redirect('/home');
}
```

> Para redirecionar para uma URL específica ao invés do referer:
> ```php
> $request = (new CadastroRequest($_POST))->redirectOnFail('/cadastro');
> ```
```

### Passo 3 — Exibir erros na View

```php
<!-- Exibir erro de um campo específico -->
<?php $err = old_error('nome'); ?>
<?php if ($err !== '') { ?>
    <span class="error-message"><?php echo htmlspecialchars($err); ?></span>
<?php } ?>

<!-- Recuperar o valor digitado anteriormente -->
<input type="text" name="nome" value="<?= old('nome') ?>">

<!-- Exibir erro de autenticação geral (ex: credenciais inválidas) -->
<div class="error-auth">{{erro}}</div>
```

---

## Todas as Regras Disponíveis

### Presença e tipo básico

| Regra      | Exemplo              | Descrição |
|------------|----------------------|-----------|
| `required` | `'required'`         | Campo não pode ser vazio |
| `nullable` | `'nullable'`         | Permite campo vazio — para a validação se vazio |
| `string`   | `'string'`           | Deve ser texto |
| `integer`  | `'integer'`          | Deve ser número inteiro |
| `numeric`  | `'numeric'`          | Deve ser numérico (int ou float) |
| `boolean`  | `'boolean'`          | Deve ser verdadeiro/falso |

```php
// Exemplo: campo obrigatório e inteiro
'idade' => ['required', 'integer', 'min:18', 'max:120'],

// Exemplo: campo opcional (nullable para na validação se vazio)
'apelido' => ['nullable', 'string', 'max:50'],
```

### Tamanho e intervalo

| Regra  | Exemplo    | Descrição |
|--------|------------|-----------|
| `min`  | `'min:3'`  | Mínimo de 3 caracteres (string), valor ≥ 3 (número) |
| `max`  | `'max:255'`| Máximo de 255 caracteres (string), valor ≤ 255 (número) |

```php
'titulo' => ['required', 'string', 'min:5', 'max:200'],
'preco'  => ['required', 'numeric', 'min:0'],
```

### Formatos especiais

| Regra    | Exemplo                       | Descrição |
|----------|-------------------------------|-----------|
| `email`  | `'email'`                     | E-mail válido + verifica DNS/MX do domínio |
| `url`    | `'url'`                       | URL válida |
| `regex`  | `'regex:/^[A-Z]{3}[0-9]{4}$/'`| Padrão personalizado |
| `in`     | `'in:ativo,inativo,pendente'` | Deve ser um dos valores listados |

```php
// Validar placa de carro
'placa' => ['required', 'regex:/^[A-Z]{3}[0-9]{4}$/'],

// Validar status permitido
'status' => ['required', 'in:ativo,inativo,suspenso'],

// Validar site
'site' => ['nullable', 'url'],
```

### Banco de dados

| Regra    | Exemplo                       | Descrição |
|----------|-------------------------------|-----------|
| `unique` | `'unique:tb_usuarios,email'`  | Valor não pode já existir na tabela |
| `exists` | `'exists:tb_periodos,id'`     | Valor deve existir na tabela |

```php
// E-mail único no cadastro
'email' => ['required', 'email', 'unique:tb_usuarios,email'],

// Garantir que periodo_id existe
'periodo_id' => ['required', 'integer', 'exists:tb_periodos,id'],
```

> **Atenção:** as regras `unique` e `exists` consultam o banco. Use somente quando necessário.

### Datas

| Regra        | Exemplo                    | Descrição |
|--------------|----------------------------|-----------|
| `date`       | `'date'`                   | Data válida em qualquer formato |
| `dateFormat` | `'dateFormat:Y-m-d'`       | Data no formato exato |
| `dateAfter`  | `'dateAfter:2000-01-01'`   | Deve ser posterior à data |
| `dateAfter`  | `'dateAfter:today'`        | Deve ser posterior a hoje |
| `dateBefore` | `'dateBefore:today'`       | Deve ser anterior a hoje |
| `dateBefore` | `'dateBefore:2030-12-31'`  | Deve ser anterior à data |

```php
'data_nascimento' => ['required', 'dateFormat:Y-m-d', 'dateBefore:today'],
'data_evento'     => ['required', 'dateFormat:Y-m-d', 'dateAfter:today'],
'inicio'          => ['required', 'date'],
```

### Arquivos (upload)

| Regra      | Exemplo                        | Descrição |
|------------|--------------------------------|-----------|
| `fileType` | `'fileType:jpg,jpeg,png,webp'` | Extensão permitida |
| `fileSize` | `'fileSize:2048'`              | Tamanho máximo em KB (2048 = 2 MB) |

```php
// Foto de perfil: imagem até 2 MB
'foto' => ['fileType:jpg,jpeg,png,webp', 'fileSize:2048'],

// PDF até 5 MB
'documento' => ['fileType:pdf', 'fileSize:5120'],
```

> **Importante:** o Request mescla `$_FILES` automaticamente quando houver arquivos.
> Sempre passe apenas `$_POST` — o arquivo chegará no `validated()` junto com os demais campos.

```php
// ✅ Correto — sempre passe só $_POST
$request = (new GovRequest($_POST))->redirectOnFail();
$data = $request->validated();
// $data['foto'] → array do arquivo já validado (se houver)

$this->govService->criarGovernador($data);

// ❌ Errado — arquivo fora do Request, sem validação
$request = (new GovRequest($_POST))->redirectOnFail();
$this->govService->criarGovernador($request->validated(), $_FILES['foto'] ?? null);
```

---

## Exemplo Real — LoginRequest

Este é o Request usado na tela de login:

```php
// src/App/Http/Requests/LoginRequest.php
class LoginRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'min:5', 'max:255'],
            'senha' => ['required', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email'    => 'Informe um e-mail válido.',
            'email.min'      => 'O e-mail deve ter no mínimo 5 caracteres.',
            'email.max'      => 'O e-mail deve ter no máximo 255 caracteres.',
            'senha.required' => 'O campo senha é obrigatório.',
            'senha.min'      => 'A senha deve ter no mínimo 8 caracteres.',
        ];
    }
}
```

### Controller (`LoginController::autenticar`)

```php
public function autenticar(): void
{
    $request = new LoginRequest($_POST);

    if ($request->fails()) {
        $_SESSION['validation_errors'] = $request->errors();
        $_SESSION['old_input']         = $_POST;
        Url::redirect('/');
        return;
    }

    $data = $request->validated();

    try {
        $user = $this->authService->auth($data['email'], $data['senha']);
        // ... login bem-sucedido
    } catch (UsuarioException $e) {
        $_SESSION['erro_login'] = $e->getMessage();
        Url::redirect('/');
    }
}
```

### View (`src/views/Login/index.php`)

```php
<!-- Campo e-mail com recuperação de valor e erro -->
<input type="email" name="email" value="<?= old('email') ?>">
<?php $err = old_error('email'); ?>
<?php if ($err !== '') { ?>
    <span class="error-message"><?php echo htmlspecialchars($err); ?></span>
<?php } ?>

<!-- Campo senha (nunca recupera o valor por segurança) -->
<input type="password" name="senha">
<?php $err = old_error('senha'); ?>
<?php if ($err !== '') { ?>
    <span class="error-message"><?php echo htmlspecialchars($err); ?></span>
<?php } ?>

<!-- Erro de autenticação (credenciais inválidas) -->
<div class="error-auth">{{erro}}</div>
```

---

## Funções Helpers disponíveis (`src/Core/Helpers.php`)

| Função           | Uso | Descrição |
|------------------|-----|-----------|
| `old('campo')`   | View | Retorna o valor digitado antes do erro (já com `htmlspecialchars`) |
| `old_error('campo')` | View | Retorna a mensagem de erro do campo (string vazia se sem erro) |
| `flash('chave')` | Controller/View | Lê e apaga um valor da session de uma vez |
| `csrf()`         | View | Gera o campo hidden com o token CSRF |

> `old()` e `old_error()` usam `static` internamente — leem da session uma só vez e limpam automaticamente. **Não precisa dar `unset` no controller.**

---

## Como personalizar mensagens

O método `messages()` aceita dois formatos de chave:

```php
public function messages(): array
{
    return [
        // formato: campo.regra  → mensagem específica para aquela regra
        'email.required' => 'Informe seu e-mail.',
        'email.email'    => 'E-mail inválido.',
        'email.min'      => 'E-mail muito curto.',

        // formato: campo  → mensagem genérica para qualquer erro do campo
        'senha' => 'Senha inválida.',
    ];
}
```

Se não houver mensagem personalizada, o sistema usa a mensagem padrão da trait.

---

## Comportamento das regras em sequência

As regras de um campo são executadas **em ordem** e **param no primeiro erro**:

```php
'email' => ['required', 'email', 'min:5', 'max:255']
//           ^1          ^2       ^3        ^4
// Se 'required' falhar → para. Não testa 'email', 'min', 'max'.
// Se 'required' passar e 'email' falhar → para. Não testa 'min', 'max'.
```

Exceção: `nullable` para tudo se o campo estiver vazio, **sem erro**:

```php
'site' => ['nullable', 'url', 'max:255']
// Se site vier vazio → campo ignorado, sem erro.
// Se site vier preenchido → valida 'url' e 'max'.
```

---

## Checklist para implementar um novo formulário

- [ ] Criar `src/App/Http/Requests/NomeDoRequest.php`
- [ ] Definir `rules()` com os campos e regras
- [ ] Definir `messages()` com mensagens em português
- [ ] No controller: instanciar o Request com `$_POST` (ou `array_merge($_POST, $_FILES)` para uploads)
- [ ] Checar `$request->fails()` e redirecionar com erros na session se necessário
- [ ] Salvar `$_SESSION['old_input'] = $_POST` junto com os erros (para repopular o form)
- [ ] Na view: usar `old('campo')` no `value` dos inputs
- [ ] Na view: usar `old_error('campo')` para exibir mensagens de erro
- [ ] Adicionar a classe CSS `error-message` no `<span>` de erro (já estilizado em vermelho)
