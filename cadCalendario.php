<?php
$nivelPermitidos = [1, 2];
require_once "validaUser.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$Calendario = new Calendario();

if (filter_has_var(INPUT_POST, "btnCadastrar")):

    $Calendario->setTitulo(filter_input(INPUT_POST, "titulo", FILTER_SANITIZE_STRING));
    $Calendario->setDescricao(filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_STRING));
    $Calendario->setDataInicio(filter_input(INPUT_POST, "dataInicio", FILTER_SANITIZE_STRING));
    $Calendario->setDataFim(filter_input(INPUT_POST, "dataFim", FILTER_SANITIZE_STRING));
    $Calendario->setCorDataCalendario(filter_input(INPUT_POST, "corDataCalendario", FILTER_SANITIZE_STRING));
    $Calendario->setLocal(filter_input(INPUT_POST, "local", FILTER_SANITIZE_STRING));
    $id = filter_input(INPUT_POST, 'id');

    if (empty($id)):
        $status = $Calendario->add() ? "add_ok" : "add_erro";
    else:
        $status = $Calendario->update('idCalendario', (int) $id) ? "edit_ok" : "edit_erro";
    endif;

    header("Location: cadCalendario.php?status=" . $status);
    exit;

elseif (filter_has_var(INPUT_POST, "btnDeletar")):

    $id = intval(filter_input(INPUT_POST, "id"));
    $status = $Calendario->delete("idCalendario", $id) ? "delete_ok" : "delete_erro";

    header("Location: cadCalendario.php?status=" . $status);
    exit;

endif;

// Mensagens exibidas no toast, de acordo com o "status" que veio na URL após o redirect
$mensagensStatus = [
    "add_ok" => ["sucesso", "Evento cadastrado com sucesso."],
    "add_erro" => ["erro", "Erro ao cadastrar o evento."],
    "edit_ok" => ["sucesso", "Evento alterado com sucesso."],
    "edit_erro" => ["erro", "Erro ao alterar o evento."],
    "delete_ok" => ["deletado", "Evento excluído com sucesso."],
    "delete_erro" => ["erro", "Erro ao excluir o evento."],
];

$statusRecebido = filter_input(INPUT_GET, "status");
$toast = $mensagensStatus[$statusRecebido] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.12.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.css">
    <link rel="stylesheet" href="CSS/calendario.css?v=<?php echo filemtime('CSS/calendario.css'); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Agenda</title>
</head>

<body>
    <?php require_once "_parts/_header2.php"; ?>

    <?php if ($toast): ?>
        <div id="toastAgenda" class="toast-agenda toast-<?php echo $toast[0]; ?>">
            <?php echo htmlspecialchars($toast[1]); ?>
        </div>
    <?php endif; ?>

    <main class="pagina-calendario">

        <div class="topo-calendario">
            <h3 class="tituloPrincipal m-0">Agenda do Coroinhas</h3>
            <button type="button" class="btn btn-vermelho rounded-pill px-4" id="btnNovoEvento">
                <i class="bi bi-plus-circle me-1"></i>Novo evento
            </button>
        </div>

        <div class="cartao-calendario">
            <div id="calendar"></div>
        </div>

        <!-- Painel só aparece quando recebe a classe "ativo" via JS -->
        <div id="painelEvento">

            <div class="painel-cabecalho">
                <h4 class="m-0" id="painelTitulo">Novo evento</h4>
                <button type="button" class="btn-close" id="btnFecharPainel" aria-label="Fechar"></button>
            </div>

            <form action="cadCalendario.php" method="post" id="formEvento" class="painel-corpo">

                <input type="hidden" name="id" id="id">

                <div class="mb-3">
                    <label class="form-label">Título</label>
                    <input type="text" class="form-control" name="titulo" id="titulo" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descrição</label>
                    <textarea class="form-control" name="descricao" id="descricao" rows="3" maxlength="250"
                        placeholder="Breve descrição. Até 250 caracteres"></textarea>
                    <div class="contador-caracteres">
                        <span id="contadorCaracteres">0</span>/250
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label">Data início</label>
                        <input type="date" class="form-control" id="dataInicioData" readonly>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Data fim</label>
                        <input type="date" class="form-control" id="dataFimData">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label">Início</label>
                        <input type="time" class="form-control" id="horaInicio" value="08:00">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Fim</label>
                        <input type="time" class="form-control" id="horaFim" value="09:00">
                    </div>
                </div>

                <input type="hidden" name="dataInicio" id="dataInicio">
                <input type="hidden" name="dataFim" id="dataFim">

                <div class="mb-3">
                    <label class="form-label">Local</label>
                    <input type="text" class="form-control" name="local" id="local">
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Cor</label>
                    <div class="opcoes-cor">

                        <input type="radio" class="btn-check" name="corDataCalendario" id="azul" value="#4285F4"checked>
                        <label class="bolinha-cor cor-azul" for="azul"></label>

                        <input type="radio" class="btn-check" name="corDataCalendario" id="verde" value="#34A853">
                        <label class="bolinha-cor cor-verde" for="verde"></label>

                        <input type="radio" class="btn-check" name="corDataCalendario" id="vermelho" value="#EA4335">
                        <label class="bolinha-cor cor-vermelho" for="vermelho"></label>

                        <input type="radio" class="btn-check" name="corDataCalendario" id="amarelo" value="#FBBC05">
                        <label class="bolinha-cor cor-amarelo" for="amarelo"></label>

                        <input type="radio" class="btn-check" name="corDataCalendario" id="preto" value="#202124">
                        <label class="bolinha-cor cor-preto" for="preto"></label>

                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-outline-success" name="btnCadastrar">
                        <i class="bi bi-check2-circle me-1"></i>Salvar
                    </button>
                    <button type="button" class="btn btn-outline-danger" id="btnExcluir" style="display:none;">
                        <i class="bi bi-trash me-1"></i>Excluir
                    </button>
                    <button type="button" class="btn btn-light btn-cancelar" id="btnCancelar">Cancelar</button>
                </div>

            </form>

            <!-- Form dedicado à exclusão. O campo hidden "btnDeletar" é o que garante o envio,
                 já que form.submit() via JS não manda o name/value de um <button> clicado. -->
            <form action="cadCalendario.php" method="post" id="formExcluir">
                <input type="hidden" name="id" id="idExcluir">
                <input type="hidden" name="btnDeletar" value="1">
            </form>

        </div>

    </main>

    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/locales-all.global.min.js"></script>
    <script src="JS/calendario.js?v=<?php echo filemtime('JS/calendario.js'); ?>"></script>
    <script src="JS/calendarioCad.js?v=<?php echo filemtime('JS/calendarioCad.js'); ?>"></script>
</body>

</html>