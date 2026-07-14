document.addEventListener("DOMContentLoaded", function () {

    const calendarEl = document.getElementById("calendar");
    const painel = document.getElementById("painelEvento");

    function formatarHoraCurta(date) {
        const h = date.getHours();
        const m = date.getMinutes();
        return m === 0 ? `${h}h` : `${h}h${String(m).padStart(2, "0")}`;
    }

    // Mostra só a hora de início do evento (ex: "19h" ou "19h30"), uma vez por evento.
    function textoDoEvento(arg) {
        if (arg.isStart) {
            return `${formatarHoraCurta(arg.event.start)} - ${arg.event.title}`;
        }
        return arg.event.title;
    }

    function formatarDataExtenso(inicio, fim) {
        const opcoesData = { day: "2-digit", month: "long", year: "numeric" };
        const opcoesHora = { hour: "2-digit", minute: "2-digit" };

        const dataInicio = inicio.toLocaleDateString("pt-BR", opcoesData);
        const horaInicio = inicio.toLocaleTimeString("pt-BR", opcoesHora);

        const mesmoDia = fim && inicio.toDateString() === fim.toDateString();

        if (!fim) {
            return `${dataInicio}, ${horaInicio}`;
        }

        if (mesmoDia) {
            const horaFim = fim.toLocaleTimeString("pt-BR", opcoesHora);
            return `${dataInicio}, ${horaInicio} às ${horaFim}`;
        }

        const dataFim = fim.toLocaleDateString("pt-BR", opcoesData);
        const horaFim = fim.toLocaleTimeString("pt-BR", opcoesHora);
        return `${dataInicio} ${horaInicio} até ${dataFim} ${horaFim}`;
    }

    const calendar = new FullCalendar.Calendar(calendarEl, {

        locale: "pt-br",
        initialView: "dayGridMonth",
        selectable: false,
        height: "auto",
        dayMaxEvents: true,

        headerToolbar: {
            left: "title",
            center: "",
            right: "prev,next"
        },

        eventDisplay: "block",

        events: "Ajax/eventosCalendario.php",

        eventClick: function (info) {
            preencherPainel(info.event);
            abrirPainel();
            eventoAtual = info.event;

            document.getElementById('btnGoogleAgenda').href = gerarLinkGoogleAgenda(info.event);

            // pega a cor do evento (o que estiver preenchido)
            const cor = info.event.backgroundColor || info.event.borderColor || info.event.extendedProps?.color || '#0d6efd';

            // seta a variável CSS no painel (ou direto nos botões)
            document.getElementById('painelEvento').style.setProperty('--cor-evento', cor);
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

    function preencherPainel(event) {

        document.getElementById("painelTitulo").textContent = event.title;

        const cor = event.extendedProps.cor || "#4285F4";
        painel.style.setProperty("--cor-evento", cor);

        const descricao = event.extendedProps.descricao;
        const descricaoWrap = document.getElementById("painelDescricaoWrap");
        if (descricao) {
            document.getElementById("painelDescricao").textContent = descricao;
            descricaoWrap.style.display = "block";
        } else {
            descricaoWrap.style.display = "none";
        }

        const inicio = event.start;
        const fim = event.end ?? null;
        document.getElementById("painelData").textContent = formatarDataExtenso(inicio, fim);

        const local = event.extendedProps.local;
        const localWrap = document.getElementById("painelLocalWrap");
        if (local) {
            document.getElementById("painelLocal").textContent = local;
            localWrap.style.display = "block";
        } else {
            localWrap.style.display = "none";
        }
    }

    function formatarDataHora(date) {
        const dia = String(date.getDate()).padStart(2, "0");
        const mes = String(date.getMonth() + 1).padStart(2, "0");
        const ano = String(date.getFullYear()).slice(-2);
        const hora = String(date.getHours()).padStart(2, "0");
        const min = String(date.getMinutes()).padStart(2, "0");
        return `${dia}/${mes}/${ano} às ${hora}:${min}`;
    }

    function formatarDataExtenso(inicio, fim) {
        if (!fim) {
            return formatarDataHora(inicio);
        }
        return `${formatarDataHora(inicio)} até ${formatarDataHora(fim)}`;
    }

    document.getElementById("btnFecharPainel").addEventListener("click", fecharPainel);

    function formatarDataGoogle(data) {
        return data.toISOString().replace(/-|:|\.\d+/g, '');
    }

    function gerarLinkGoogleAgenda(evento) {
        const inicio = formatarDataGoogle(new Date(evento.start));
        const fim = formatarDataGoogle(new Date(evento.end || evento.start));

        const params = new URLSearchParams({
            action: 'TEMPLATE',
            text: evento.title || '',
            dates: `${inicio}/${fim}`,
            details: evento.extendedProps?.descricao || '',
            location: evento.extendedProps?.local || ''
        });

        return `https://www.google.com/calendar/render?${params.toString()}`;
    }
});