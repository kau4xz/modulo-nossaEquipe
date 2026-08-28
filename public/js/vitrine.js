const searchInput = document.getElementById('searchInput');
const filtertype = document.getElementById('filterType');
const filterStatus = document.getElementById('filterStatus');
const cards = document.querySelectorAll('#vitrineTableBody .js-vitrine-card');

function filtrar(){
    const search = searchInput.value.toLocaleLowerCase().trim();
    const type = filtertype.value;
    const status = filterStatus.value;

    cards.forEach(card => {
        const matchSearch = !search || card.dataset.nome.includes(search);
        const matchType = !type || card.dataset.type === type;
        const matchStatus = !status || card.dataset.status === status;
        const show        = matchSearch && matchType && matchStatus;

        card.style.display = show ? '' : 'none';
    })
}

searchInput.addEventListener('input', filtrar);
filtertype.addEventListener('change', filtrar);
filterStatus.addEventListener('change', filtrar);