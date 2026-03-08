<?php
require_once "validaUser.php";
?>
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
    <?php require_once "_parts/_header2.php"; ?>
    
    <main class="container my-3">
        <div class="mt-3">
            <h3 class="tituloPrincipal">Coroinhas</h3>
        </div>
        <div class="mt-3 mb-3">
            <a href="coroinha.php" class="btn btn-outline-success">Novo Coroinha</a>
        </div>

        <table class="tableaG dataTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="text-center">Nome</th>
                    <th class="text-center">Nível</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                spl_autoload_register(function ($class) {
                    require_once "Classes/{$class}.class.php";
                });
                $c = new Coroinha();
                $Coroinhas = $c->all();
                foreach ($Coroinhas as $coroinha):
                    ?>
                    <tr>
                        <td><?php echo $coroinha->idCoroinha ?></td>
                        <td><?php echo $coroinha->nomeCoroinha ?></td>
                        <td><?php echo $coroinha->nivel ?></td>
                        <td><?php echo $coroinha->status ?></td>
                        <td class="text-center d-flex gap-1 justify-content-center">
                            <form action="<?php echo htmlspecialchars("coroinha.php") ?>" method="post" class="d-flex">
                                <input type="hidden" name="id" value="<?php echo $coroinha->idCoroinha ?>">
                                <button title="Editar" name="btnEditar" class="btn btn-primary btn-sm" type="submit"
                                    onclick="return confirm('Tem certeza que deseja editar o coroinha?');">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </form>
                            <form action="<?php echo htmlspecialchars("coroinha.php") ?>" method="post" class="d-flex">
                                <input type="hidden" name="id" value="<?php echo $coroinha->idCoroinha ?>">
                                <button title="Deletar" name="btnDeletar" class="btn btn-danger btn-sm" type="submit"
                                    onclick="return confirm('Tem certeza que deseja deletar o coroinha?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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