(function () {
    var modal     = document.getElementById('modalExcluir');
    if (!modal) return;

    var nomeEl    = document.getElementById('modalItemNome');
    var inputId   = document.getElementById('modalItemId');
    var form      = document.getElementById('formModalExcluir');
    var btnCancel = document.getElementById('btnCancelarExcluir');

    function abrir(btn) {
        nomeEl.textContent = btn.dataset.nome  || '';
        inputId.value      = btn.dataset.id    || '';
        inputId.name       = btn.dataset.campo || 'id';
        form.action        = btn.dataset.url   || '';

        // Remove extra inputs de chamadas anteriores
        form.querySelectorAll('.modal-extra').forEach(function (el) { el.remove(); });

        // Adiciona campos extras (ex.: periodo_id) via data-extras='{"campo":"valor"}'
        if (btn.dataset.extras) {
            try {
                var extras = JSON.parse(btn.dataset.extras);
                Object.keys(extras).forEach(function (name) {
                    var inp = document.createElement('input');
                    inp.type      = 'hidden';
                    inp.className = 'modal-extra';
                    inp.name      = name;
                    inp.value     = extras[name];
                    form.appendChild(inp);
                });
            } catch (e) {}
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
    }

    function fechar() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
    }

    document.querySelectorAll('.js-btn-excluir').forEach(function (btn) {
        btn.addEventListener('click', function () { abrir(btn); });
    });

    if (btnCancel) btnCancel.addEventListener('click', fechar);

    modal.addEventListener('click', function (e) {
        if (e.target === modal) fechar();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') fechar();
    });
}());
