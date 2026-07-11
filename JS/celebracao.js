const diaSemana = document.getElementById("diaSemana");
const semana = document.getElementById("semana");
const turno = document.getElementById("turno");
const data = document.getElementById("data");

const camposDomSeg = document.querySelectorAll(".campoDomSeg");
const dataSexta = document.querySelector(".dataSexta");

function mostraDadosProfissional() {

    if (diaSemana.value === "Domingo" || diaSemana.value === "Segunda") {
        camposDomSeg.forEach(campo => campo.style.display = "block");
        dataSexta.style.display = "none";
        semana.required = true;
        turno.required = true;
        data.required = false;
    } else if (diaSemana.value === "Sexta") {
        camposDomSeg.forEach(campo => campo.style.display = "none");
        dataSexta.style.display = "block";
        semana.required = false;
        turno.required = false;
        data.required = true;
    } else {
        camposDomSeg.forEach(campo => campo.style.display = "none");
        dataSexta.style.display = "none";
    }
}

mostraDadosProfissional();
diaSemana.addEventListener("change", mostraDadosProfissional);