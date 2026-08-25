const searchInput  = document.getElementById('searchInput');
const filterStatus = document.getElementById('filterStatus');
const categoria = document.getElementById('categoria');
const rows         = document.querySelectorAll('#integranteTableBody tr[data-nome]');
const totalVisible = document.getElementById('totalVisible');
const semResultado = document.getElementById('semResultado');
const sortSelect = document.getElementById('sortSelect');


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


function ordenar() {
    const sortSelect = document.getElementById('sortSelect'); 
    const sortValue = sortSelect?.value ?? ''; 
    
    
    if (!sortValue || rows.length === 0) return;

    const container = rows[0].parentNode; 
    const rowsArray = Array.from(rows);

    rowsArray.sort((a, b) => {
        let valorA, valorB;


        if (sortValue.startsWith('nome')) {
            valorA = (a.dataset.nome || '').toLowerCase();
            valorB = (b.dataset.nome || '').toLowerCase();
        } 
        else if (sortValue.startsWith('criado')) {
            const dataA = (a.dataset.criado || '').replace(' ', 'T');
            const dataB = (b.dataset.criado || '').replace(' ', 'T');
            valorA = new Date(dataA).getTime() || 0;
            valorB = new Date(dataB).getTime() || 0;
        } 
        else if (sortValue.startsWith('atualizado')) {
            const dataA = (a.dataset.atualizado || '').replace(' ', 'T');
            const dataB = (b.dataset.atualizado || '').replace(' ', 'T');
            valorA = new Date(dataA).getTime() || 0;
            valorB = new Date(dataB).getTime() || 0;
        }

     
        const direcao = sortValue.endsWith('_desc') ? -1 : 1;

        if (valorA < valorB) return -1 * direcao;
        if (valorA > valorB) return 1 * direcao;
        return 0; // Empate
    });

    rowsArray.forEach(row => container.appendChild(row));
}
document.getElementById('sortSelect')?.addEventListener('change', ordenar);


