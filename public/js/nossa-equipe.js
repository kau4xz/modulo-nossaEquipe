const searchInput  = document.getElementById('searchInput');
const filterStatus = document.getElementById('filterStatus');
const rows         = document.querySelectorAll('#integranteTableBody tr[data-nome]');
const totalVisible = document.getElementById('totalVisible');
const semResultado = document.getElementById('semResultado');

function filtrar() {
    const search = (searchInput?.value ?? '').toLowerCase().trim();
    const status = filterStatus?.value ?? '';
    let visible  = 0;

    rows.forEach(row => {
        const matchSearch = !search
            || row.dataset.nome.includes(search)
            || row.dataset.cargo.includes(search);
        const matchStatus = !status || row.dataset.status === status;
        const show        = matchSearch && matchStatus;

        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    if (totalVisible) {
        totalVisible.textContent = `${visible} integrante(s)`;
    }

    if (semResultado) {
        semResultado.classList.toggle('hidden', visible > 0 || rows.length === 0);
    }
}

searchInput?.addEventListener('input', filtrar);
filterStatus?.addEventListener('change', filtrar);

filtrar();

