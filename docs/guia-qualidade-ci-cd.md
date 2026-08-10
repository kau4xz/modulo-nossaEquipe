# Guia de Qualidade de Código e CI/CD

> Para estagiários e novos membros do time.  
> Este documento explica tudo que foi configurado para garantir a qualidade do código neste projeto: Git Hooks, PHP CodeSniffer, PHP Insights, PHPStan e a pipeline do GitLab CI/CD.

---

## Sumário

1. [Por que tudo isso existe?](#1-por-que-tudo-isso-existe)
2. [Git Hooks](#2-git-hooks)
   - [O que são Git Hooks](#o-que-são-git-hooks)
   - [Como instalar](#como-instalar)
   - [pre-commit — Espelha todas as validações do CI](#pre-commit--espelha-todas-as-validações-do-ci)
   - [commit-msg — Valida o formato da mensagem](#commit-msg--valida-o-formato-da-mensagem)
   - [pre-push — Bloqueia push em branches protegidas](#pre-push--bloqueia-push-em-branches-protegidas)
3. [PHP CodeSniffer (phpcs)](#3-php-codesniffer-phpcs)
   - [O que é o PSR-12](#o-que-é-o-psr-12)
   - [Como rodar manualmente](#como-rodar-manualmente)
   - [Como corrigir automaticamente](#como-corrigir-automaticamente)
   - [O arquivo phpcs.xml](#o-arquivo-phpcsxml)
4. [PHPStan](#4-phpstan)
   - [O que é análise estática](#o-que-é-análise-estática)
   - [Como rodar](#como-rodar)
   - [O arquivo phpstan.neon](#o-arquivo-phpstanneon)
5. [PHP Insights](#5-php-insights)
   - [O que mede](#o-que-mede)
   - [Como rodar](#como-rodar-1)
   - [O arquivo phpinsights.php](#o-arquivo-phpinsightsphp)
6. [Conventional Commits](#6-conventional-commits)
7. [Pipeline GitLab CI/CD](#7-pipeline-gitlab-cicd)
   - [O que é CI/CD](#o-que-é-cicd)
   - [Como a pipeline funciona](#como-a-pipeline-funciona)
   - [Job: php:syntax](#job-phpsyntax)
   - [Job: php:codesniffer](#job-phpcodesniffer)
   - [Job: commit:message](#job-commitmessage)
   - [Job: php:stan](#job-phpstan)
   - [Job: php:insights](#job-phpinsights)
8. [Template de Merge Request](#8-template-de-merge-request)
9. [Fluxo completo de trabalho](#9-fluxo-completo-de-trabalho)
10. [Problemas comuns e soluções](#10-problemas-comuns-e-soluções)

---

## 1. Por que tudo isso existe?

Quando várias pessoas trabalham no mesmo projeto, é fácil que o código fique inconsistente: cada um escrevendo de um jeito diferente, sem padrão de nomes, com funções gigantes, mensagens de commit sem sentido, etc.

Para evitar isso, usamos ferramentas automáticas que:

- **Bloqueiam** código fora do padrão antes mesmo de ele entrar no repositório
- **Validam** automaticamente toda vez que alguém abre um Merge Request
- **Documentam** o que foi feito através de mensagens de commit padronizadas

O fluxo é: seu computador → Git Hooks → GitLab → Pipeline → Code Review → merge

---

## 2. Git Hooks

### O que são Git Hooks

Git Hooks são scripts que o Git executa automaticamente em momentos específicos: antes de um commit, antes de um push, etc. Eles ficam na pasta `.git/hooks/` do repositório.

O problema é que `.git/` não é versionado — ou seja, quando alguém clona o repositório, os hooks não vêm junto. Por isso criamos a pasta `.githooks/` (que é versionada) e um script de instalação.

### Como instalar

**Todo novo membro do time precisa rodar esse comando uma única vez após clonar o repositório:**

```bash
bash .githooks/install.sh
```

Isso copia os hooks de `.githooks/` para `.git/hooks/` e os torna executáveis.

### pre-commit — Espelha todas as validações do CI

**Arquivo:** `.githooks/pre-commit`

Este hook roda automaticamente quando você executa `git commit` e replica localmente todas as verificações que a pipeline faz no GitLab. A ideia é **falhar rápido** — melhor descobrir o problema antes de commitar do que esperar a pipeline rodar.

**O que ele executa em ordem:**

| Verificação | Comportamento se falhar |
|---|---|
| Branch protegida | Bloqueia o commit |
| Sintaxe PHP (`php -l`) | Bloqueia o commit |
| PHP CodeSniffer (PSR-12) | Bloqueia o commit |
| PHPStan (análise estática) | Bloqueia o commit |
| PHP Insights (qualidade) | Apenas avisa, não bloqueia |

**Branch protegida:**
```
[BLOQUEADO] Commits diretos na branch 'main' não são permitidos.
Crie uma branch de feature e abra um Merge Request.
```

Branches protegidas: `desenvolvimento`, `estruturacao`, `develop`, `main`, `master`

Por quê? Porque código vai para produção através dessas branches. Ninguém deve commitar direto nelas — todo código precisa passar por revisão via Merge Request.

**Por que o PHP Insights só avisa?** Ele analisa o projeto inteiro e pode ser lento. Como o score varia com o contexto e é mais subjetivo, optamos por não bloquear — mas o aviso incentiva o time a manter a qualidade.

### commit-msg — Valida o formato da mensagem

**Arquivo:** `.githooks/commit-msg`

Valida se a mensagem do commit segue o padrão **Conventional Commits** (explicado na seção 5).

Se a mensagem estiver errada:
```
[ERRO] Formato de commit inválido.

Formato esperado: <tipo>(<escopo>): <descrição>

Exemplos válidos:
  feat(auth): adiciona login com OAuth
  fix(router): corrige redirecionamento 404
  docs: atualiza README com instruções de instalação
```

### pre-push — Bloqueia push em branches protegidas

**Arquivo:** `.githooks/pre-push`

Mesmo que alguém consiga commitar em uma branch protegida (ex: desativando o hook), o push ainda será bloqueado:

```
[BLOQUEADO] Push direto para 'main' não é permitido.
Abra um Merge Request para integrar suas alterações.
```

---

## 3. PHP CodeSniffer (phpcs)

O **PHP CodeSniffer** analisa o código PHP e aponta violações de estilo e formatação.

### O que é o PSR-12

**PSR-12** é um padrão de codificação PHP amplamente adotado pelo mercado. Ele define regras como:

- Indentação com 4 espaços (nunca tab)
- Abertura de chave `{` na mesma linha da declaração
- Uma instrução por linha
- Espaço após operadores de cast: `(int) $x` e não `(int)$x`
- Comprimento máximo de linha (120 caracteres como aviso, 150 como erro)
- etc.

Seguir um padrão faz com que qualquer pessoa do time consiga ler o código de outra sem precisar se adaptar ao estilo pessoal de cada um.

### Como rodar manualmente

```bash
# Verificar todos os arquivos do projeto
vendor/bin/phpcs --standard=phpcs.xml

# Verificar um arquivo específico
vendor/bin/phpcs --standard=phpcs.xml src/App/Models/User.php

# Ver o relatório completo com cores
vendor/bin/phpcs --standard=phpcs.xml --report=full --colors
```

### Como corrigir automaticamente

O **phpcbf** (PHP Code Beautifier and Fixer) corrige automaticamente a maioria das violações:

```bash
# Corrigir todos os arquivos
vendor/bin/phpcbf --standard=phpcs.xml

# Corrigir um arquivo específico
vendor/bin/phpcbf --standard=phpcs.xml src/App/Models/User.php
```

> **Atenção:** nem tudo é corrigível automaticamente. Erros de lógica ou estrutura precisam ser corrigidos manualmente.

### O arquivo phpcs.xml

Localizado na raiz do projeto, configura o comportamento do phpcs:

```xml
<ruleset name="Projeto">
    <!-- Usa o padrão PSR-12 como base -->
    <rule ref="PSR12"/>

    <!-- Analisa estes diretórios -->
    <file>src</file>
    <file>public/index.php</file>

    <!-- Ignora estes diretórios -->
    <exclude-pattern>vendor/*</exclude-pattern>

    <!-- Limite de tamanho de linha: aviso em 120, erro em 150 -->
    <!-- Views HTML são excluídas porque têm atributos longos inevitáveis -->
    <rule ref="Generic.Files.LineLength">
        <exclude-pattern>src/views/*</exclude-pattern>
        <exclude-pattern>src/App/Utils/EmailSend.php</exclude-pattern>
    </rule>

    <!-- Proíbe funções de debug no código -->
    <rule ref="Generic.PHP.ForbiddenFunctions">
        <!-- var_dump, print_r, var_export, die são proibidos -->
    </rule>
</ruleset>
```

**Por que excluímos as views?** Arquivos de template HTML frequentemente têm linhas longas com atributos HTML, classes CSS inline e strings de conteúdo — forçar o limite de 120 caracteres quebraria a legibilidade do HTML.

---

## 4. PHPStan

O **PHPStan** é uma ferramenta de análise estática — ela lê o código sem executar e detecta erros de tipo, variáveis inexistentes, retornos incorretos e outros bugs que só apareceriam em tempo de execução.

### O que é análise estática

Imagine que você tem uma função que espera receber um `int`, mas em algum lugar do código você passa uma `string`. O PHP não vai reclamar em tempo de execução até aquela linha ser executada — e dependendo do fluxo, isso pode demorar para aparecer em produção.

O PHPStan detecta esse tipo de problema **antes de rodar o código**, lendo apenas os tipos declarados.

**Exemplo de erro que o PHPStan pega:**
```php
// IUserService::getUserById() espera int
public function getUserById(int $id): User { ... }

// Mas AdminController passa string (vinda da URL)
$user = $this->userService->getUserById($id); // $id é string aqui
// PHPStan: Parameter #1 expects int, string given
```

### Como rodar

```bash
# Analisar com a configuração do projeto
vendor/bin/phpstan analyse --configuration=phpstan.neon

# Rodar sem barra de progresso (mais limpo)
vendor/bin/phpstan analyse --configuration=phpstan.neon --no-progress

# Ver erros de um arquivo específico
vendor/bin/phpstan analyse src/App/Http/Controllers/AdminController.php --level=5
```

### O arquivo phpstan.neon

```yaml
parameters:
    level: 5          # Nível de rigor (0 = mínimo, 10 = máximo)
    paths:
        - src/App     # Analisa apenas App e Core
        - src/Core
    excludePaths:
        - src/views   # Views têm variáveis injetadas via extract() — falsos positivos
        - src/routes  # Arquivos de rota têm variáveis do contexto do router
```

**Por que level 5?** O PHPStan tem 11 níveis (0–10). Level 5 é um bom equilíbrio — pega erros reais de tipo sem ser excessivamente rigoroso sobre anotações de docblock. O time pode aumentar o nível gradualmente.

**Por que excluir views e routes?** As views recebem variáveis via `extract($vars)`, que o PHPStan não consegue rastrear estaticamente — geraria dezenas de falsos positivos sobre variáveis "não definidas".

---

## 5. PHP Insights

O **PHP Insights** vai além da formatação e analisa a qualidade geral do código em 4 dimensões:

| Dimensão | O que mede |
|---|---|
| **Code** | Boas práticas: variáveis não usadas, operadores incorretos, falta de tipos, etc. |
| **Complexity** | Complexidade ciclomática — quão difícil é entender e testar o código |
| **Architecture** | Estrutura: uso de interfaces, tamanho de funções, organização de classes |
| **Style** | Estilo de código: aspas, imports ordenados, espaçamentos, etc. |

**Score mínimo exigido:** 80% em cada dimensão.

### Como rodar

```bash
# Analisar o diretório src/
vendor/bin/phpinsights analyse src --no-interaction

# Ver apenas os erros (sem warnings)
vendor/bin/phpinsights analyse src --no-interaction --min-quality=80 --min-complexity=80 --min-architecture=80 --min-style=80
```

### O arquivo phpinsights.php

Localizado na raiz do projeto, configura regras que são excluídas por serem muito rígidas para a realidade do projeto:

- **ForbiddenNormalClasses** — exigiria que todas as 30+ classes fossem `final` ou `abstract`. Isso é uma boa prática em sistemas grandes, mas muito invasivo para um projeto em crescimento.
- **ForbiddenTraits** — o arquivo `Validations.php` usa traits intencionalmente para organizar as regras de validação.
- **DisallowShortTernaryOperator** — o operador `?:` é válido e legível em PHP.
- O diretório `src/views` é excluído da análise porque são templates HTML misturados com PHP.

---

## 6. Conventional Commits

Mensagens de commit padronizadas permitem:
- Entender o histórico do projeto sem ler o código
- Gerar changelogs automaticamente
- Fazer code review mais eficiente

**Formato obrigatório:**
```
<tipo>(<escopo opcional>): <descrição curta>
```

**Tipos válidos:**

| Tipo | Quando usar |
|---|---|
| `feat` | Nova funcionalidade |
| `fix` | Correção de bug |
| `docs` | Apenas documentação |
| `style` | Formatação sem mudança de lógica |
| `refactor` | Refatoração sem nova feature nem fix |
| `test` | Adição ou correção de testes |
| `chore` | Tarefas de manutenção (build, dependências) |
| `build` | Mudanças no sistema de build |
| `ci` | Mudanças na configuração de CI/CD |
| `perf` | Melhoria de performance |
| `revert` | Reverte um commit anterior |

**Exemplos:**

```bash
# ✅ Corretos
git commit -m "feat(auth): adiciona autenticação OAuth com Google"
git commit -m "fix(router): corrige loop infinito no redirecionamento 404"
git commit -m "docs: atualiza guia de instalação no README"
git commit -m "style: corrige formatação PSR-12 em GovController"
git commit -m "chore: atualiza dependências do composer"
git commit -m "ci: adiciona job de validação de commits na pipeline"

# ❌ Errados
git commit -m "correção"
git commit -m "WIP"
git commit -m "arrumei o bug"
git commit -m "update"
```

---

## 7. Pipeline GitLab CI/CD

### O que é CI/CD

**CI (Continuous Integration)** = toda vez que você abre um Merge Request, o GitLab automaticamente executa uma série de verificações no seu código.

**CD (Continuous Delivery/Deployment)** = se tudo passar, o código pode ser entregue/publicado automaticamente.

No nosso caso, usamos o CI para garantir que nenhum código com problemas chegue às branches principais.

**Sem CI/CD:**
```
Desenvolvedor → commit → main → bug em produção
```

**Com CI/CD:**
```
Desenvolvedor → MR → Pipeline (testes automáticos) → Code Review → main → produção
```

### Como a pipeline funciona

O arquivo `.gitlab-ci.yml` na raiz do projeto define toda a pipeline. Ela tem dois estágios:

```
validate ──► quality
```

- **validate**: roda em paralelo — sintaxe PHP, phpcs e validação de commits
- **quality**: roda depois — PHPStan e PHP Insights (ambos com `allow_failure: true`)

A pipeline só roda em **Merge Requests** e em pushes para `main`/`master`.

### Job: php:syntax

```yaml
php:syntax:
  stage: validate
  image: php:8.4-cli
```

**O que faz:** Verifica se todos os arquivos `.php` têm sintaxe válida.

**Por que existe:** Um erro de sintaxe (ex: falta de `;`, parêntese não fechado) impede que a aplicação funcione. Este job é rápido e pega esses erros básicos antes de qualquer outra coisa.

**Exemplo de erro que pega:**
```php
// Faltou fechar o parêntese
echo str_replace("a", "b", $texto;
```

### Job: php:codesniffer

```yaml
php:codesniffer:
  stage: validate
  image: php:8.4-cli
  extends:
    - .composer-cache
    - .install-deps
```

**O que faz:** Roda o `vendor/bin/phpcs` com o padrão PSR-12 configurado em `phpcs.xml`.

**Por que o `extends`?** Reutiliza duas configurações:
- `.composer-cache` → salva a pasta `vendor/` em cache para não precisar baixar tudo de novo toda vez
- `.install-deps` → instala o `git`, `unzip`, `composer` e roda `composer install`

**Por que instalar git e unzip?** A imagem `php:8.4-cli` é mínima — vem só com o PHP. O Composer precisa do `git` e do `unzip` para baixar alguns pacotes.

**Artifacts:** Gera um relatório `phpcs-report.xml` no formato JUnit que o GitLab consegue exibir diretamente na interface do MR.

### Job: commit:message

```yaml
commit:message:
  stage: validate
  image: alpine:latest
  rules:
    - if: '$CI_PIPELINE_SOURCE == "merge_request_event"'
```

**O que faz:** Verifica se todas as mensagens de commit do MR seguem o padrão Conventional Commits.

**Por que só roda em MR?** Faz sentido validar commits apenas quando eles vão ser integrados a uma branch principal. Em pushes para branches de feature, isso seria muito restritivo.

**Detalhe técnico importante:** O script usa `< <(comando)` em vez de `comando | while read` para evitar um problema de subshell no bash — variáveis modificadas dentro de um pipe não são visíveis fora dele.

```bash
# ❌ Errado — FAILED sempre fica 0 (subshell)
comando | while read linha; do FAILED=1; done; exit $FAILED

# ✅ Correto — FAILED é modificado no shell pai
while read linha; do FAILED=1; done < <(comando); exit $FAILED
```

### Job: php:stan

```yaml
php:stan:
  stage: quality
  allow_failure: true
```

**O que faz:** Roda o PHPStan com o nível 5 nos diretórios `src/App` e `src/Core`, conforme configurado em `phpstan.neon`.

**Artifacts:** Gera um `phpstan-report.json` no formato Code Quality do GitLab — os erros aparecem diretamente nas linhas do diff do MR, facilitando a revisão.

**Por que `allow_failure: true`?** O PHPStan pode reportar erros em código legado que ainda não foi tipado corretamente. Colocamos como informativo para não travar o time enquanto a base de código é melhorada gradualmente.

### Job: php:insights

```yaml
php:insights:
  stage: quality
  allow_failure: true
```

**O que faz:** Roda a análise completa de qualidade com PHP Insights nas 4 dimensões (Code, Complexity, Architecture, Style), exigindo mínimo de 80% em cada uma.

**Por que `allow_failure: true`?** O PHP Insights é uma análise mais subjetiva e profunda. Usamos `allow_failure` para que o MR não seja bloqueado por issues de qualidade — mas o desenvolvedor consegue ver o relatório e trabalhar para melhorar. Com o tempo, o time pode remover o `allow_failure` à medida que o código amadurece.

---

## 8. Template de Merge Request

Ao abrir um novo Merge Request no GitLab, a descrição é preenchida automaticamente com o template em `.gitlab/merge_request_templates/Default.md`.

Ele pede:
- **O que foi feito** — descrição das mudanças
- **Motivação** — por que a mudança foi necessária
- **Tipo de mudança** — feature, fix, refactor, etc.
- **Como testar** — passos para validar
- **Checklist** — confirmações obrigatórias antes do merge

Preencher o template direto agiliza o code review e documenta decisões que não ficam no código.

---

## 9. Fluxo completo de trabalho

```
1. Criar branch a partir de main/develop
   git checkout -b feat/minha-funcionalidade

2. Desenvolver e adicionar arquivos
   git add src/App/...

3. Commitar (hooks rodam automaticamente)
   git commit -m "feat(modulo): descrição"
   
   ↓ pre-commit: sintaxe → phpcs → phpstan → phpinsights (aviso)
   ↓ commit-msg: valida o formato da mensagem

4. Push da branch
   git push origin feat/minha-funcionalidade
   
   ↓ pre-push: garante que não é branch protegida

5. Abrir Merge Request no GitLab
   ↓ Pipeline roda automaticamente:
     [validate]  php:syntax, php:codesniffer, commit:message
     [quality]   php:stan (informativo), php:insights (informativo)

6. Code review pelo time

7. Merge aprovado → integrado à branch alvo
```

---

## 10. Como implementar o CI em um projeto novo

Esta seção ensina como replicar toda essa estrutura em um novo projeto PHP do zero.

### Pré-requisitos

- Repositório no GitLab com pelo menos um runner disponível (verifique em **Settings → CI/CD → Runners**)
- PHP 8.4+ e Composer instalados localmente

### Passo 1 — Instalar as ferramentas via Composer

```bash
# Ferramentas obrigatórias
composer require --dev squizlabs/php_codesniffer phpstan/phpstan

# Ferramentas opcionais (análise de qualidade)
composer require --dev nunomaduro/phpinsights
```

### Passo 2 — Configurar o PHP CodeSniffer

Crie `phpcs.xml` na raiz do projeto:

```xml
<?xml version="1.0"?>
<ruleset name="Nome do Projeto">
    <file>src</file>
    <exclude-pattern>vendor/*</exclude-pattern>
    <rule ref="PSR12"/>
    <rule ref="Generic.Files.LineLength">
        <properties>
            <property name="lineLimit" value="120"/>
            <property name="absoluteLineLimit" value="150"/>
        </properties>
        <!-- Excluir views/templates HTML do limite de linha -->
        <exclude-pattern>src/views/*</exclude-pattern>
    </rule>
</ruleset>
```

Teste rodando:
```bash
vendor/bin/phpcs --standard=phpcs.xml
```

### Passo 3 — Configurar o PHPStan

Crie `phpstan.neon` na raiz:

```yaml
parameters:
    level: 5
    paths:
        - src/App
        - src/Core
    excludePaths:
        - src/views
        - src/routes
```

Teste rodando:
```bash
vendor/bin/phpstan analyse --configuration=phpstan.neon --no-progress
```

> Comece no level 3 ou 4 se o projeto tiver muito código legado. Vá aumentando o nível gradualmente.

### Passo 4 — Criar os Git Hooks

Crie a pasta `.githooks/` na raiz e copie os três arquivos deste projeto:

```
.githooks/
├── pre-commit    # Sintaxe + phpcs + phpstan + phpinsights
├── commit-msg    # Valida formato Conventional Commits
├── pre-push      # Bloqueia push em branches protegidas
└── install.sh    # Script de instalação
```

Dê permissão de execução:
```bash
chmod +x .githooks/pre-commit .githooks/commit-msg .githooks/pre-push .githooks/install.sh
```

> Edite a lista `PROTECTED_BRANCHES` nos hooks `pre-commit` e `pre-push` para incluir as branches do seu projeto.

### Passo 5 — Instalar os hooks localmente

```bash
bash .githooks/install.sh
```

Cada membro do time precisa rodar isso uma vez após clonar o repositório.

### Passo 6 — Criar a pipeline GitLab

Crie `.gitlab-ci.yml` na raiz copiando o arquivo deste projeto e ajuste:

1. **A imagem PHP** — use a mesma versão que seu `composer.lock` foi gerado. Verifique com `php -v`.
2. **As branches** nos `rules` — ajuste `main`/`master` para as branches do seu projeto.
3. **Os caminhos** no job `php:stan` e `php:insights` — aponte para os diretórios corretos do seu projeto.

Verifique a tag do runner disponível em **Settings → CI/CD → Runners** e ajuste:
```yaml
default:
  tags:
    - docker  # substitua pela tag do seu runner
```

### Passo 7 — Criar o template de Merge Request

Crie `.gitlab/merge_request_templates/Default.md` com o template desejado. O GitLab carrega automaticamente ao abrir um novo MR.

### Passo 8 — Verificar que tudo funciona

```bash
# 1. Teste os hooks localmente
git add src/
git commit -m "feat: teste"   # deve rodar phpcs + phpstan

# 2. Faça push e abra um MR no GitLab
# A pipeline deve aparecer e rodar os jobs

# 3. Verifique os resultados em:
#    MR → Pipeline → cada job
```

### Checklist de implementação

- [ ] `phpcs.xml` criado e testado localmente
- [ ] `phpstan.neon` criado e testado localmente
- [ ] Hooks em `.githooks/` com permissão de execução
- [ ] `bash .githooks/install.sh` rodado em todas as máquinas do time
- [ ] `.gitlab-ci.yml` com a imagem PHP correta e tag do runner certa
- [ ] Template de MR em `.gitlab/merge_request_templates/Default.md`
- [ ] Pipeline rodando com sucesso no GitLab

---

## 11. Problemas comuns e soluções

### "Meu commit foi bloqueado pelo phpcs"

```bash
# Ver exatamente o que está errado
vendor/bin/phpcs --standard=phpcs.xml

# Corrigir automaticamente o que for possível
vendor/bin/phpcbf --standard=phpcs.xml

# Adicionar as correções e tentar o commit novamente
git add .
git commit -m "style: corrige formatação PSR-12"
```

### "Minha mensagem de commit foi rejeitada"

O formato esperado é `tipo(escopo): descrição`. Exemplos:
```bash
git commit -m "feat(usuarios): adiciona filtro por status"
git commit -m "fix: corrige erro 500 na página de login"
```

### "A pipeline falhou no php:codesniffer"

1. Abra o job no GitLab e leia o relatório
2. Corrija os erros localmente com `vendor/bin/phpcbf`
3. Verifique que não restou nada: `vendor/bin/phpcs --standard=phpcs.xml`
4. Commit e push das correções

### "Os hooks não estão funcionando após clonar o repositório"

Os hooks precisam ser instalados manualmente:
```bash
bash .githooks/install.sh
```

### "Quero fazer um commit de emergência sem os hooks"

Em situações excepcionais (ex: reverter um deploy quebrado):
```bash
git commit --no-verify -m "revert: reverte deploy quebrado de 2026-05-01"
```

> **Use com moderação.** O `--no-verify` pula todos os hooks. Sempre documente o motivo no commit.

### "A pipeline está travada (stuck)"

Significa que nenhum runner está disponível. Verifique em **GitLab → Settings → CI/CD → Runners** se o runner de instância está online e configurado para aceitar jobs sem tag ou com a tag `docker`.
