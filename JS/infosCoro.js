document.getElementById('filtroStatus').addEventListener('change', function () {

    const status = this.value;
    const cards = document.querySelectorAll('.card-coroinha');

    cards.forEach(card => {

        if (status === '' || card.dataset.status === status) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }

    });

});