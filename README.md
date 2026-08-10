# Framework MVC PHP — Base Modular para Equipes

Projeto base para desenvolvimento em equipe usando o padrão MVC com PHP puro.
Cada módulo é isolado e independente, permitindo que membros da equipe trabalhem em paralelo sem conflitos.

---

## Arquitetura

```
estruturabaseMvcPhp/
├── public/               # Ponto de entrada (index.php), assets (css, js, images)
│   └── index.php         # Bootstrap: carrega .env → DI → Router
│
└── src/
    ├── Core/             # Framework — NÃO edite sem necessidade
    │   ├── Router.php        Roteador HTTP com suporte a middleware
    │   ├── Container.php     Container de injeção de dependência
    │   ├── Database.php      Wrapper PDO
    │   ├── Request.php       Validação de formulários
    │   ├── Auth.php          Sessão e autenticação
    │   ├── Csrf.php          Proteção CSRF
    │   ├── FileService.php   Upload e conversão de imagens (WebP)
    │   ├── Logger.php        Log em arquivo
    │   └── Helpers.php       Funções globais: csrf(), old(), flash()
    │
    └── App/
        ├── Models/           Uma classe por entidade de domínio
        ├── Enums/            Status, Perfil, HttpStatus
        ├── Http/
        │   ├── Controllers/  Um controller por módulo
        │   ├── Middleware/   Auth, Admin, Guest, RateLimit
        │   ├── Requests/     Validação de formulários por módulo
        │   └── Exceptions/   Exceções de domínio por módulo
        ├── Services/
        │   ├── IServices/    Interfaces de serviço
        │   └── Auditoria/    Decorators de auditoria (opcional)
        ├── Infrastructure/
        │   ├── IRepositories/ Interfaces de repositório
        │   └── Repositories/  Implementações PDO
        ├── Utils/            View, Url, Toast
        └── views/            Templates PHP por módulo
```

---

## Como criar um novo módulo

Cada módulo segue o **padrão de 10 arquivos**. Copie o módulo `Exemplo` e renomeie para o seu domínio.

```
Módulo: Produto
├── src/App/Models/Produto.php
├── src/App/Http/Controllers/ProdutoController.php
├── src/App/Services/ProdutoService.php
├── src/App/Services/IServices/IProdutoService.php
├── src/App/Infrastructure/Repositories/ProdutoRepository.php
├── src/App/Infrastructure/IRepositories/IProdutoRepository.php
├── src/App/Http/Requests/ProdutoRequest.php
├── src/App/Http/Exceptions/Produto/ProdutoException.php
├── src/routes/modulos/produto.php
├── src/config/dependencies/modulos/produto.php
└── src/views/Produto/  (index.php, criar.php, editar.php)
```

### Passos

1. **Copie** os arquivos do módulo `Exemplo` e renomeie as classes/namespaces
2. **Adicione a tabela** no banco com a estrutura `tb_nome_modulo`
3. **Registre a rota** no `src/routes/modulos/novomodulo.php` — é descoberta automaticamente
4. **Registre o DI** no `src/config/dependencies/modulos/novomodulo.php` — descoberto automaticamente
5. **Adicione o link** no menu em `src/views/Shared/sidebar.php`

> Os arquivos de rotas e DI são descobertos automaticamente via `glob()` — não é preciso registrá-los manualmente.

---

## Trabalho em equipe

O projeto é projetado para minimizar conflitos de merge:

| Membro | Responsabilidade |
|--------|-----------------|
| Dev A  | Módulo `Produto` — cria seus 10 arquivos isolados |
| Dev B  | Módulo `Cliente` — cria seus 10 arquivos isolados |
| Dev C  | Módulo `Pedido`  — cria seus 10 arquivos isolados |

Nenhum módulo toca nos arquivos do outro. O único arquivo compartilhado no dia a dia é o `sidebar.php` (menu de navegação).

---

## Setup inicial

```bash
# 1. Clone o repositório
git clone <url-do-repo>

# 2. Instale as dependências PHP
composer install

# 3. Instale as dependências de front-end (Tailwind CSS)
npm install
npm run build

# 4. Configure o ambiente
cp .env.example .env
# Edite .env com suas credenciais de banco

# 5. Importe o banco de dados base
mysql -u root -p < src/database/base.sql

# 6. Aponte o Apache para a pasta public/
# DocumentRoot: .../estruturabaseMvcPhp/public
```

---

## Ferramentas de qualidade

```bash
# Análise estática
vendor/bin/phpstan analyse src

# Padrão de código PSR-12
vendor/bin/phpcs src

# Testes unitários
vendor/bin/phpunit

# Métricas de qualidade
vendor/bin/phpinsights
```

---

## Variáveis de ambiente

| Variável             | Descrição                    |
|----------------------|------------------------------|
| `DB_HOST`            | Host do banco de dados        |
| `DB_NAME`            | Nome do banco de dados        |
| `DB_USER`            | Usuário do banco              |
| `DB_PASS`            | Senha do banco                |

---

## Leia mais

- [`docs/como-criar-modulo.md`](docs/como-criar-modulo.md) — guia detalhado com código de exemplo
- [`docs/arquitetura.md`](docs/arquitetura.md) — decisões de arquitetura
- [`docs/boas-praticas.md`](docs/boas-praticas.md) — convenções do projeto
