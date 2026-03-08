document.addEventListener("DOMContentLoaded", function () {

    /* pegar todas as células que terão cor */
    const celulas = document.querySelectorAll("td[data-nivel]");

    /* aplicar cores ao carregar página */
    celulas.forEach(td => {
        aplicarCor(td);
    });

});

/* ===== FUNÇÃO PARA APLICAR CORES ===== */

function aplicarCor(td) {
    const nivel = td.dataset.nivel; // pega o valor do atributo data-nivel
    td.classList.remove("nivel1", "nivel2", "acolito"); // limpa classes anteriores

    if(nivel === "Acólito"){
        td.classList.add("acolito");
    } else if(nivel === "Nível 2"){
        td.classList.add("nivel2");
    } else if(nivel === "Nível 1"){
        td.classList.add("nivel1");
    }
}