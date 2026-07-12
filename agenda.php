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
    <?php require_once "_parts/_header.php"; ?>

    <main class="pagina-calendario">

        <div class="topo-calendario">
            <h3 class="tituloPrincipal m-0">Agenda do Coroinhas</h3>
        </div>

        <div class="cartao-calendario">
            <div id="calendar"></div>
        </div>

        <div id="painelEvento">

            <div class="painel-cabecalho">
                <h4 class="m-0" id="painelTitulo"></h4>
                <button type="button" class="btn-close" id="btnFecharPainel" aria-label="Fechar"></button>
            </div>

            <div class="painel-corpo">

                <div class="mb-3" id="painelDescricaoWrap">
                    <label class="form-label d-block">Descrição</label>
                    <p id="painelDescricao" class="mb-0"></p>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Quando</label>
                    <p id="painelData" class="mb-0"></p>
                </div>

                <div class="mb-3" id="painelLocalWrap">
                    <label class="form-label d-block">Local</label>
                    <p id="painelLocal" class="mb-0"></p>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/locales-all.global.min.js"></script>
    <script src="JS/calendario.js?v=<?php echo filemtime('JS/calendario.js'); ?>"></script>
</body>

</html>