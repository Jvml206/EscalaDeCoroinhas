<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="CSS/tabelas.css?v=<?php echo filemtime('CSS/tabelas.css'); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Coroinhas</title>
</head>

<body>
    <?php require_once "_parts/_header.php"; ?>
    <main class="container my-3">
        <div class="mt-3">
            <h3 class="tituloPrincipal">Acólitos</h3>
        </div>

        <div class="table-responsive tabela-scroll">
            <table class="tableaA dataTable">
                <thead>
                    <tr>
                        <th class="align-middle text-center">Nome</th>
                        <th class="text-center">Número de Vezes Servindo</th>
                        <th class="align-middle text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    spl_autoload_register(function ($class) {
                        require_once "Classes/{$class}.class.php";
                    });
                    $c = new Coroinha();
                    $Coroinhas = $c->searchStr("nivel", "Acólito");
                    foreach ($Coroinhas as $coroinha):
                        ?>
                        <tr>
                            <td><?php echo $coroinha->nomeCoroinha ?></td>
                            <td class="text-center"><?php echo $coroinha->numeroServindo ?></td>
                            <td><?php echo $coroinha->status ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="JS/paginacao.js"></script>
</body>

</html>