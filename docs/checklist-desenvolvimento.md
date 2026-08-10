# 🧠 Guia de Auto-Reflexão: Antes de Codar e Publicar

Este guia contém perguntas fundamentais que todo desenvolvedor (especialmente estagiários) deve se fazer antes de iniciar uma tarefa e antes de enviar o código para revisão. O objetivo é garantir a **qualidade**, **segurança** e **manutenibilidade** do projeto.

---

## 1. Planejamento (Antes de começar)
*   **Eu entendi o problema?** Consigo explicar a funcionalidade em uma frase para alguém que não é técnico?
*   **Já existe algo pronto?** Existe alguma função, Helper ou Model que já faz parte disso? (Evite duplicar lógica).
*   **Qual o impacto?** Onde mais essa alteração mexe? Afeta o login? Afeta a visualização pública?
*   **Como eu vou testar?** Antes de escrever a primeira linha, eu já sei qual o caminho feliz e o caminho de erro?

## 2. Qualidade do Código (Durante o desenvolvimento)
*   **Nomes são claros?** Daqui a 6 meses, eu saberei o que a variável `$data` ou a função `process()` faz? (Prefira nomes semânticos como `$dataNascimento` ou `atualizarStatusGovernador()`).
*   **Funções curtas?** Minha função faz apenas UMA coisa ou ela é um "canivete suíço" gigante?
*   **Código limpo?** Removi `console.log`, `var_dump`, comentários de código morto ou funções de teste antes de finalizar?
*   **DRY (Don't Repeat Yourself):** Eu copiei e colei esse bloco de código? Se sim, ele deveria ser um componente ou uma função compartilhada?

## 3. Segurança (Crítico) 🔒
*   **Validação de Input:** Eu confiei no que vem do usuário? (Todo dado vindo de `$_POST`, `$_GET` ou `fetch` deve ser validado e sanitizado).
*   **CSRF:** Se criei um formulário novo, ele possui o token de proteção contra ataques cross-site?
*   **Autorização:** Eu verifiquei se o usuário logado realmente TEM PERMISSÃO para executar essa ação ou ver esse dado? (Não confie apenas em esconder o botão no front-end).
*   **SQL Injection:** Usei *Prepared Statements* ou o Query Builder do projeto? **Nunca** concatene variáveis diretamente em strings SQL.
*   **Dados Sensíveis:** Existe algum risco de eu estar exibindo senhas, tokens ou dados pessoais que não deveriam estar ali?

## 4. Experiência do Usuário (UX)
*   **Feedback de Erro:** Se algo falhar (ex: banco fora do ar), o usuário recebe uma mensagem amigável ou a tela simplesmente "trava" ou exibe um erro de código?
*   **Loading:** Para ações demoradas, eu dei um feedback visual (spinner/desabilitar botão) para o usuário não clicar duas vezes?
*   **Mobile:** Essa tela nova funciona bem em um celular ou só na minha tela de monitor gigante?

## 5. Revisão Final (Checklist de PR)
- [ ] Removi comentários desnecessários e código de debug.
- [ ] O código segue a indentação e o padrão do projeto.
- [ ] As variáveis e funções estão em português (conforme padrão do projeto).
- [ ] Testei o fluxo completo manualmente.
- [ ] Verifiquei se não deixei nenhuma credencial ou segredo no código.

---
> **Reflexão de Ouro:** "Escreva código como se a pessoa que fosse manter o seu sistema depois de você fosse um psicopata que sabe onde você mora." - *John Woods*
