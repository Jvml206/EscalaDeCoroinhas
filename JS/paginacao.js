$(document).ready(function () {
    $('.dataTable').dataTable(
        {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.3.4/i18n/pt-BR.json',
            },
            responsive: true,
            pageLength: 15,
            lengthMenu: [5, 10, 15, 25, 50, 100],
            order: [[3, 'desc']],
            columnDefs: [
            { targets: 'no-search', searchable: false },
            { targets: 'no-order', orderable: false }
        ]
        }
    );
});