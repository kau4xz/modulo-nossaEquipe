

const DOM = {
    form: document.querySelector('form'),
    senhaInput: document.querySelector('#senha'),
    btnVerSenha: document.querySelector('#btnVerSenha'),
    btnSubmit: document.querySelector('button[type="submit"]'),
    authError: document.querySelector('#auth-error')
};


function togglePassword() {
    if (!DOM.senhaInput || !DOM.btnVerSenha) return;

    const icon = DOM.btnVerSenha.querySelector('i');
    const isPassword = DOM.senhaInput.type === 'password';

    DOM.senhaInput.type = isPassword ? 'text' : 'password';

    if (icon) {
        icon.classList.toggle('ph-eye', isPassword);
        icon.classList.toggle('ph-eye-slash', !isPassword);
    }
}

function setLoadingState(isLoading) {
    if (!DOM.btnSubmit) return;

    if (isLoading) {

        DOM.btnSubmit.disabled = true;
        DOM.btnSubmit.classList.add('opacity-70', 'cursor-not-allowed', 'pointer-events-none');
        DOM.btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Entrando...';
    } else {
        DOM.btnSubmit.disabled = false;
        DOM.btnSubmit.classList.remove('opacity-70', 'cursor-not-allowed', 'pointer-events-none');
        DOM.btnSubmit.innerHTML = '<i class="fa-solid fa-right-to-bracket"></i> Entrar';
    }
}


function init() {
    
    if (DOM.btnVerSenha) {
        DOM.btnVerSenha.addEventListener('click', togglePassword);
    }

    
    if (DOM.form) {
        DOM.form.addEventListener('submit', () => setLoadingState(true));
    }

    
    if (DOM.authError && DOM.authError.textContent.trim() !== '') {
        DOM.authError.classList.remove('hidden');
    }
}

document.addEventListener('DOMContentLoaded', init);
