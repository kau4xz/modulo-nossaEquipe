#!/bin/bash
# Instala os git hooks do projeto

HOOKS_DIR=".githooks"
GIT_HOOKS_DIR=".git/hooks"

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

if [ ! -d ".git" ]; then
    echo -e "${RED}[ERRO]${NC} Execute este script na raiz do repositório."
    exit 1
fi

echo "Instalando git hooks..."

for hook in "$HOOKS_DIR"/*; do
    hook_name=$(basename "$hook")
    [ "$hook_name" = "install.sh" ] && continue

    cp "$hook" "$GIT_HOOKS_DIR/$hook_name"
    chmod +x "$GIT_HOOKS_DIR/$hook_name"
    echo -e "  ${GREEN}✓${NC} $hook_name"
done

# Configura o hooksPath para o diretório .githooks (alternativa ao cp)
# git config core.hooksPath .githooks

echo -e "\n${GREEN}Hooks instalados com sucesso!${NC}"
echo -e "Branches protegidas: ${YELLOW}desenvolvimento, estruturacao, develop, main, master${NC}"
