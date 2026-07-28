import {
  construirListadeRequisitosSenha,
  checarRequisitosSenha,
  allRequirementsPass,
  validatePassword,
  hideFieldError,
  validateConfirmPassword,
} from "./utils/util.js";

const DOM = {
  form: document.querySelector(".form"),
  novaSenha: document.getElementById("nova_senha"),
  confirmarSenha: document.getElementById("confirmar_senha"),
  toggleButtons: document.querySelectorAll(".toggle-password"),
  listaSenha: document.getElementById("obrigatoriaSenha"),
  codeInputs: document.querySelectorAll(".code-input"),
  codigoCompleto: document.getElementById("codigoCompleto"),
  btnVerificarCodigo: document.getElementById("btnVerificarCodigo"),
  btnReenviar: document.getElementById("btnReenviar"),
  timerReenviar: document.getElementById("timerReenviar"),
  formCodigo: document.getElementById("formCodigo"),
  codeError: document.getElementById("codeError"),
};

// ============================================
// TOGGLE PASSWORD
// ============================================

function togglePassword(button) {
  const targetId = button.getAttribute("data-target");
  const input = document.getElementById(targetId);
  const icon = button.querySelector("i");

  if (!input || !icon) return;

  const isPassword = input.type === "password";
  input.type = isPassword ? "text" : "password";
  icon.classList.toggle("fa-eye", !isPassword);
  icon.classList.toggle("fa-eye-slash", isPassword);
}

// ============================================
// VALIDAÇÃO DO FORM DE NOVA SENHA
// ============================================

function validateForm(e) {
  if (!DOM.novaSenha || !DOM.confirmarSenha) return true;

  const novaSenha = DOM.novaSenha.value;
  const confirmarSenha = DOM.confirmarSenha.value;

  if (!allRequirementsPass(novaSenha)) {
    e.preventDefault();
    DOM.novaSenha.focus();
    return false;
  }

  if (novaSenha !== confirmarSenha) {
    e.preventDefault();
    alert("As senhas não coincidem!");
    DOM.confirmarSenha.focus();
    return false;
  }

  return true;
}

// ============================================
// CÓDIGO DE VERIFICAÇÃO - 6 DÍGITOS
// ============================================

function getCodeValue() {
  let code = "";
  DOM.codeInputs.forEach(function (input) {
    code += input.value;
  });
  return code;
}

function updateSubmitButton() {
  const code = getCodeValue();
  const allFilled = code.length === 6 && /^\d{6}$/.test(code);

  if (DOM.btnVerificarCodigo) {
    DOM.btnVerificarCodigo.disabled = !allFilled;
  }
}

function handleCodeInput(e) {
  const input = e.target;
  const value = input.value;

  input.value = value.replace(/[^0-9]/g, "");

  if (input.value.length === 1) {
    const index = parseInt(input.dataset.index);
    const nextInput = document.querySelector(
      '.code-input[data-index="' + (index + 1) + '"]',
    );
    if (nextInput) {
      nextInput.focus();
    }
  }

  updateSubmitButton();
  hideCodeError();
}

function handleCodeKeydown(e) {
  const input = e.target;
  const index = parseInt(input.dataset.index);

  if (e.key === "Backspace") {
    if (input.value === "") {
      const prevInput = document.querySelector(
        '.code-input[data-index="' + (index - 1) + '"]',
      );
      if (prevInput) {
        prevInput.focus();
        prevInput.value = "";
      }
    } else {
      input.value = "";
    }
    updateSubmitButton();
    e.preventDefault();
  }

  if (e.key === "ArrowLeft") {
    const prevInput = document.querySelector(
      '.code-input[data-index="' + (index - 1) + '"]',
    );
    if (prevInput) prevInput.focus();
  }

  if (e.key === "ArrowRight") {
    const nextInput = document.querySelector(
      '.code-input[data-index="' + (index + 1) + '"]',
    );
    if (nextInput) nextInput.focus();
  }
}

function handleCodePaste(e) {
  e.preventDefault();
  const pastedData = (e.clipboardData || window.clipboardData)
    .getData("text")
    .trim();
  const digits = pastedData.replace(/[^0-9]/g, "");

  if (digits.length === 0) return;

  DOM.codeInputs.forEach(function (input, i) {
    if (i < digits.length) {
      input.value = digits[i];
    }
  });

  const focusIndex = Math.min(digits.length, DOM.codeInputs.length) - 1;
  DOM.codeInputs[focusIndex].focus();

  updateSubmitButton();
}

function handleCodeFocus(e) {
  e.target.select();
}

function handleCodeFormSubmit(e) {
  const code = getCodeValue();

  if (code.length !== 6 || !/^\d{6}$/.test(code)) {
    e.preventDefault();
    showCodeError();
    return false;
  }

  if (DOM.codigoCompleto) {
    DOM.codigoCompleto.value = code;
  }

  return true;
}

function showCodeError() {
  if (DOM.codeError) {
    DOM.codeError.style.display = "flex";
  }
  DOM.codeInputs.forEach(function (input) {
    input.classList.remove("border-slate-200");
    input.classList.add("border-rose-500", "text-rose-600", "animate-shake");
  });
}

function hideCodeError() {
  if (DOM.codeError) {
    DOM.codeError.style.display = "none";
  }
  DOM.codeInputs.forEach(function (input) {
    input.classList.add("border-slate-200");
    input.classList.remove("border-rose-500", "text-rose-600", "animate-shake");
  });
}

// ============================================
// TIMER DE REENVIO
// ============================================

function startResendTimer() {
  if (!DOM.btnReenviar || !DOM.timerReenviar) return;

  var resendDiv = document.querySelector(".code-resend");
  var expiraTimestamp = resendDiv ? parseInt(resendDiv.dataset.expira) : 0;

  if (!expiraTimestamp) {
    DOM.btnReenviar.disabled = false;
    DOM.timerReenviar.textContent = "";
    return;
  }

  function updateTimer() {
    var now = Math.floor(Date.now() / 1000);
    var seconds = expiraTimestamp - now;

    if (seconds <= 0) {
      clearInterval(interval);
      DOM.btnReenviar.disabled = false;
      DOM.timerReenviar.textContent = "";
      return;
    }

    DOM.btnReenviar.disabled = true;
    var minutes = Math.floor(seconds / 60);
    var remainingSeconds = seconds % 60;
    var formattedSeconds =
      remainingSeconds < 10 ? "0" + remainingSeconds : remainingSeconds;
    DOM.timerReenviar.textContent =
      "(" + minutes + ":" + formattedSeconds + ")";
  }

  updateTimer();
  var interval = setInterval(updateTimer, 1000);
}

// ============================================
// BLOQUEAR DUPLO CLIQUE NO SUBMIT
// ============================================

function handleFormSubmitOnce(e) {
  var btn = e.target.querySelector('button[type="submit"]');
  if (!btn) return;

  if (btn.dataset.submitting === "true") {
    e.preventDefault();
    return false;
  }

  btn.dataset.submitting = "true";
  btn.classList.add("opacity-70", "cursor-not-allowed", "pointer-events-none");
  btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Aguarde...';
}

// ============================================
// TOAST
// ============================================

function fecharToast() {
  const toast = document.getElementById("toast");
  if (toast) {
    toast.classList.add("opacity-0");
    setTimeout(function () {
      toast.parentElement.remove();
    }, 300);
  }
}

document.addEventListener("DOMContentLoaded", function () {
  DOM.toggleButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      togglePassword(this);
    });
  });

  if (DOM.novaSenha && DOM.confirmarSenha) {
    construirListadeRequisitosSenha(DOM.listaSenha);

    DOM.novaSenha.addEventListener("blur", () => {
      validatePassword(DOM.novaSenha);
    });

    DOM.novaSenha.addEventListener("input", () => {
      hideFieldError(DOM.novaSenha);
      DOM.listaSenha.style.display = DOM.novaSenha.value.length > 0 ? "flex" : "none";
      checarRequisitosSenha(DOM.listaSenha, DOM.novaSenha.value);
    });
    DOM.confirmarSenha.addEventListener("blur", () => {
      validateConfirmPassword(DOM.confirmarSenha, DOM.novaSenha);
    });
    DOM.confirmarSenha.addEventListener("input", () => {
      hideFieldError(DOM.confirmarSenha);
    });
  }

  if (DOM.form) {
    DOM.form.addEventListener("submit", validateForm);
  }

  if (DOM.codeInputs.length > 0) {
    DOM.codeInputs.forEach(function (input) {
      input.addEventListener("input", handleCodeInput);
      input.addEventListener("keydown", handleCodeKeydown);
      input.addEventListener("paste", handleCodePaste);
      input.addEventListener("focus", handleCodeFocus);
    });

    DOM.codeInputs[0].focus();
  }

  if (DOM.formCodigo) {
    DOM.formCodigo.addEventListener("submit", handleCodeFormSubmit);
  }

  if (DOM.btnReenviar) {
    startResendTimer();
  }

  // Bloquear duplo submit em todos os forms
  document.querySelectorAll(".form").forEach(function (form) {
    form.addEventListener("submit", handleFormSubmitOnce);
  });

  const toast = document.getElementById("toast");
  if (toast) {
    setTimeout(function () {
      fecharToast();
    }, 5000);
  }
});
