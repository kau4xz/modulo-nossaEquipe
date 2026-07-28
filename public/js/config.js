import {
    construirListadeRequisitosSenha,
    checarRequisitosSenha,
    allRequirementsPass,
    showFieldError,
    hideFieldError,
    validateConfirmPassword,
} from './utils/util.js';

const DOM = {
    form:           document.getElementById('form-alterar-senha'),
    senhaAtual:     document.getElementById('senha_atual'),
    novaSenha:      document.getElementById('nova_senha'),
    confirmarSenha: document.getElementById('confirmar_senha'),
    listaSenha:     document.getElementById('senhaRequisitos'),
    toggleBtns:     document.querySelectorAll('.toggle-password'),
};

// ===========================================
// TOGGLE VISIBILIDADE DAS SENHAS
// ===========================================

DOM.toggleBtns.forEach(btn => {
    btn.addEventListener('click', function () {
        const input = document.getElementById(this.dataset.target);
        if (!input) return;

        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';

        const icon = this.querySelector('i');
        if (icon) {
            icon.classList.toggle('fa-eye',       isPassword);
            icon.classList.toggle('fa-eye-slash',  !isPassword);
        }
    });
});

// ===========================================
// REQUISITOS DA NOVA SENHA (tempo real)
// ===========================================

construirListadeRequisitosSenha(DOM.listaSenha);

DOM.novaSenha?.addEventListener('input', () => {
    const val = DOM.novaSenha.value;
    hideFieldError(DOM.novaSenha);

    DOM.listaSenha.style.display = val.length > 0 ? 'flex' : 'none';
    if (val.length > 0) checarRequisitosSenha(DOM.listaSenha, val);
});

DOM.senhaAtual?.addEventListener('input',     () => hideFieldError(DOM.senhaAtual));
DOM.confirmarSenha?.addEventListener('input', () => hideFieldError(DOM.confirmarSenha));

// ===========================================
// VALIDAÇÃO NO SUBMIT
// ===========================================

DOM.form?.addEventListener('submit', (e) => {
    let valido = true;

    if (!DOM.senhaAtual?.value.trim()) {
        showFieldError(DOM.senhaAtual, 'A senha atual é obrigatória');
        valido = false;
    }

    const nova = DOM.novaSenha?.value ?? '';

    if (!nova) {
        showFieldError(DOM.novaSenha, 'A nova senha é obrigatória');
        valido = false;
    } else if (!allRequirementsPass(nova)) {
        showFieldError(DOM.novaSenha, 'A senha não atende todos os requisitos');
        DOM.listaSenha.style.display = 'flex';
        checarRequisitosSenha(DOM.listaSenha, nova);
        valido = false;
    }

    if (!validateConfirmPassword(DOM.confirmarSenha, DOM.novaSenha)) {
        valido = false;
    }

    if (!valido) e.preventDefault();
});