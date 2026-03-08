<?php
spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$dado = new Coroinha();
$dados = $dado->sp_exibir('dashboard();');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/baseSite.css?v=<?php echo filemtime('CSS/baseSite.css'); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Dados</title>
</head>

<body>
    <?php require_once "_parts/_header.php"; ?>
    <main class="container py-4 mt-2">
        <h1 class="h1-dash">Dados Coroinhas Com. N. S. de Fátima</h1>
        <div class="row g-4 d-flex justify-content-center">
            <div class="col-md-3">
                <div class="card-modern text-center p-3">
                    <span class="icon">🙌</span>
                    <h5>Coroinhas</h5>
                    <h2><?= $dados[0]->coroinhasServindo ?></h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-modern text-center p-3">
                    <span class="icon">⚖️</span>
                    <h5>Média de Vezes Servindo</h5>
                    <h2><?= number_format($dados[0]->mediaServindo, 2, ',', '.'); ?></h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-modern text-center p-3">
                    <span class="icon">⛪</span>
                    <h5>Total de Comunidades</h5>
                    <h2><?= $dados[0]->totalComunidades ?></h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-modern text-center p-3">
                    <span class="icon">🧔🏻</span>
                    <h5>Total de Celebrações</h5>
                    <h2><?= $dados[0]->totalCelebracoes ?></h2>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>