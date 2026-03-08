document.addEventListener("DOMContentLoaded", function () {

    const selects = document.querySelectorAll("select[name^='escala']");

    /* aplicar cores ao carregar página */
    selects.forEach(select=>{
        aplicarCor(select);
        atualizarOpcoesLinha(select);
    });

    /* evento ao mudar seleção */
    selects.forEach(select => {
        select.addEventListener("change", function(){
            aplicarCor(this);
            atualizarOpcoesLinha(this);
        });
    });

});

/* ===== CORES ===== */

function aplicarCor(select){

    let option = select.options[select.selectedIndex];
    if(!option) return;
    let nivel = option.dataset.nivel;
    select.classList.remove("nivel1","nivel2","acolito");
    if(nivel === "Acólito"){
        select.classList.add("acolito");
    }
    if(nivel === "Nível 2"){
        select.classList.add("nivel2");
    }
    if(nivel === "Nível 1"){
        select.classList.add("nivel1");
    }
}

/* ===== REMOVER COROINHA REPETIDO NA MESMA MISSA ===== */

function atualizarOpcoesLinha(selectAtual){

    const linha = selectAtual.closest("tr");
    const selects = linha.querySelectorAll("select[name^='escala']");

    /* pegar selecionados da linha */
    let selecionados = [];

    selects.forEach(select=>{
        if(select.value !== ""){
            selecionados.push(select.value);
        }
    });

    /* esconder opções repetidas */
    selects.forEach(select=>{
        const valorAtual = select.value;

        select.querySelectorAll("option").forEach(option=>{

            if(option.value === "") return;

            if(option.value === valorAtual){
                option.hidden = false;
            }else if(selecionados.includes(option.value)){
                option.hidden = true;
            }else{
                option.hidden = false;
            }

        });
    });
}