<?php
require_once "validaUser.php";

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$coro = new Coroinha();
$coroinhas = $coro->allInfos();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/infos.css?v=<?php echo filemtime('CSS/infos.css'); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Informações dos Coroinhas</title>
</head>

<body>
    <?php require_once "_parts/_header2.php"; ?>

    <main class="container py-4 mt-2">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-md">
                <h1 class="h1-dash mb-3 mb-md-0">Informações dos Coroinhas</h1>
            </div>

            <div class="col-12 col-md-3 d-flex justify-content-center justify-content-md-end">
                <div class="status-filter">
                    <select class="form-select" id="filtroStatus">
                        <option value="">Todos os Status</option>
                        <option value="Servindo">Servindo</option>
                        <option value="Ex-coroinha">Ex-coroinha</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row g-4" id="listaCoroinhas">

            <?php foreach ($coroinhas as $coroinha): ?>
                <div class="col-xl-3 col-lg-4 col-md-6 card-coroinha"
                    data-status="<?= htmlspecialchars($coroinha->status) ?>">
                    <?php
                    $classeNivel = match ($coroinha->nivel) {
                        'Nível 1' => 'nivel1CardInfos',
                        'Nível 2' => 'nivel2CardInfos',
                        'Acólito' => 'acolitoCardInfos',
                        default => ''
                    }; ?>
                    <div class="card-infos p-4 <?= $classeNivel ?>">
                        <?php ?>
                        <h4><?= htmlspecialchars($coroinha->nomeCoroinha) ?></h4>
                        <div class="info">
                            <strong>Nível:</strong>
                            <?= htmlspecialchars($coroinha->nivel) ?>
                        </div>
                        <div class="info">
                            <strong>Status:</strong>
                            <?= htmlspecialchars($coroinha->status) ?>
                        </div>
                        <div class="info">
                            <strong>Preferência de Turno:</strong>
                            <?= htmlspecialchars($coroinha->preferenciaTurno) ?>
                        </div>
                        <div class="info">
                            <strong>Preferência de Domingo:</strong>
                            <?= htmlspecialchars($coroinha->preferenciaDomingo) ?>
                        </div>
                        <div class="info">
                            <strong>Pode Servir Segunda:</strong>
                            <?= $coroinha->podeSegunda ? "Sim" : "Não" ?>
                        </div>
                        <div class="info">
                            <strong>Nº de Vezes Servindo:</strong>
                            <?= htmlspecialchars($coroinha->numeroServindo) ?>
                        </div>
                        <div class="info">
                            <strong>Obs.:</strong>
                            <?= ''; //htmlspecialchars($coroinha->observacoes) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="JS/infosCoro.js"></script>
</body>

</html>