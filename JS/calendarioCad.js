document.addEventListener("DOMContentLoaded", function () {

    const calendarEl = document.getElementById("calendar");
    const painel = document.getElementById("painelEvento");
    const form = document.getElementById("formEvento");
    const painelTitulo = document.getElementById("painelTitulo");
    const btnExcluir = document.getElementById("btnExcluir");

    // Contador de caracteres para o campo de descrição
    const descricaoInput = document.getElementById("descricao");
    const contador = document.getElementById("contadorCaracteres");

    function atualizarContador() {
        contador.textContent = descricaoInput.value.length;
    }

    descricaoInput.addEventListener("input", atualizarContador);

    function formatarHoraCurta(date) {
        const h = date.getHours();
        const m = date.getMinutes();
        return m === 0 ? `${h}h` : `${h}h${String(m).padStart(2, "0")}`;
    }

    function formatarData(date) {
        const ano = date.getFullYear();
        const mes = String(date.getMonth() + 1).padStart(2, "0");
        const dia = String(date.getDate()).padStart(2, "0");
        return `${ano}-${mes}-${dia}`;
    }

    // Mostra só a hora de início do evento (ex: "19h" ou "19h30"), uma vez por evento.
    function textoDoEvento(arg) {
        if (arg.isStart) {
            return `${formatarHoraCurta(arg.event.start)} - ${arg.event.title}`;
        }
        return arg.event.title;
    }

    const calendar = new FullCalendar.Calendar(calendarEl, {

        locale: "pt-br",
        initialView: "dayGridMonth",
        selectable: true,
        unselectAuto: false,
        height: "auto",
        dayMaxEvents: true,

        // Sem o botão "today" no toolbar
        headerToolbar: {
            left: "title",
            center: "",
            right: "prev,next"
        },

        // "block" garante que o evento sempre apareça como bloco colorido,
        // inclusive quando começa e termina no mesmo dia.
        eventDisplay: "block",

        events: "Ajax/eventosCalendario.php",

        // Cobre clique único e arraste por vários dias: pega o primeiro e o
        // último dia selecionados e preenche início/fim do formulário.
        select: function (info) {
            resetarCorFormulario();

            novoEvento();

            // info.end é exclusivo (dia seguinte ao último selecionado)
            const ultimoDia = new Date(info.end);
            ultimoDia.setDate(ultimoDia.getDate() - 1);

            document.getElementById("dataInicioData").value = formatarData(info.start);
            document.getElementById("dataFimData").value = formatarData(ultimoDia);
            document.getElementById("horaInicio").value = "08:00";
            document.getElementById("horaFim").value = "09:00";

            abrirPainel();
            calendar.unselect();
        },

        eventClick: function (info) {
            preencherFormularioEdicao(info.event);
            abrirPainel();
        },

        eventClassNames: function (info) {
            if (info.isStart && info.isEnd) return ["evento-unico"];
            if (info.isStart) return ["evento-inicio"];
            if (info.isEnd) return ["evento-fim"];
            return ["evento-meio"];
        },

        eventContent: function (arg) {
            return { html: `<div class="evento-texto">${textoDoEvento(arg)}</div>` };
        }

    });

    calendar.render();

    function abrirPainel() {
        painel.classList.add("ativo");
    }

    function fecharPainel() {
        painel.classList.remove("ativo");
    }

    function novoEvento() {
        form.reset();
        document.getElementById("id").value = "";
        document.getElementById("dataInicioData").value = "";
        document.getElementById("dataFimData").value = "";
        document.getElementById("horaInicio").value = "08:00";
        document.getElementById("horaFim").value = "09:00";
        painelTitulo.textContent = "Novo evento";
        btnExcluir.style.display = "none";
        atualizarContador();
    }

    function resetarCorFormulario() {
        const corPadrao = "#EA4335"; // vermelho

        // desmarca todos e marca o vermelho
        document.querySelectorAll('input[name="corDataCalendario"]').forEach(input => {
            input.checked = (input.value === corPadrao);
        });

        painel.style.setProperty("--cor-evento", corPadrao);
    }

    function preencherFormularioEdicao(event) {

        document.getElementById("id").value = event.id;
        document.getElementById("titulo").value = event.title;
        document.getElementById("descricao").value = event.extendedProps.descricao ?? "";
        document.getElementById("local").value = event.extendedProps.local ?? "";

        const inicio = event.start;
        const fim = event.end ?? event.start;

        document.getElementById("dataInicioData").value = formatarData(inicio);
        document.getElementById("dataFimData").value = formatarData(fim);
        document.getElementById("horaInicio").value = inicio.toTimeString().slice(0, 5);
        document.getElementById("horaFim").value = fim.toTimeString().slice(0, 5);

        const cor = event.extendedProps.cor || "#EA4335";
        const radio = document.querySelector(`input[name="corDataCalendario"][value="${cor}"]`);
        if (radio) radio.checked = true;

        painel.style.setProperty("--cor-evento", cor);
        painelTitulo.textContent = "Editar evento";
        btnExcluir.style.display = "block";
        document.getElementById("idExcluir").value = event.id;
        atualizarContador();
    }

    document.getElementById("btnFecharPainel").addEventListener("click", fecharPainel);
    document.getElementById("btnCancelar").addEventListener("click", fecharPainel);

    document.getElementById("btnNovoEvento").addEventListener("click", function () {
        novoEvento();
        abrirPainel();
    });

    btnExcluir.addEventListener("click", function () {
        if (confirm("Deseja realmente excluir este evento?")) {
            document.getElementById("formExcluir").submit();
        }
    });

    form.addEventListener("submit", function () {

        document.getElementById("dataInicio").value =
            document.getElementById("dataInicioData").value + " " +
            document.getElementById("horaInicio").value + ":00";

        document.getElementById("dataFim").value =
            document.getElementById("dataFimData").value + " " +
            document.getElementById("horaFim").value + ":00";
    });

    // Toast de sucesso/erro (cadastro, edição, exclusão), some sozinho após 5s
    const toast = document.getElementById("toastAgenda");
    if (toast) {
        requestAnimationFrame(() => toast.classList.add("visivel"));

        setTimeout(() => {
            toast.classList.remove("visivel");
            setTimeout(() => toast.remove(), 300);
        }, 5000);

        // Limpa o "?status=" da URL pra não reaparecer se a página for recarregada
        const url = new URL(window.location.href);
        url.searchParams.delete("status");
        window.history.replaceState({}, "", url);
    }

});