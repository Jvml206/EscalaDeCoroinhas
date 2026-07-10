<?php
$nivelPermitidos = [1, 2];
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
    <title>Celebrações</title>
</head>

<body>
    <?php require_once "_parts/_header2.php"; ?>
    <main class="container my-3">
        <div class="mt-3">
            <h3 class="tituloPrincipal">Celebrações</h3>
        </div>
        <div class="mt-3 mb-3">
            <a href="celebracao.php" class="btn btn-outline-success">Nova celebração</a>
        </div>

        <div class="table-responsive tabela-scroll">
            <table class="tableaCe dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="text-center">Comunidade</th>
                        <th class="text-center">Semana/Dia</th>
                        <th class="text-center">Turno</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    spl_autoload_register(function ($class) {
                        require_once "Classes/{$class}.class.php";
                    });
                    $c = new Celebracao();
                    $Celebracoes = $c->all();

                    $Comunidade = new Comunidade();
                    $comunidade = $Comunidade->all();
                    foreach ($Celebracoes as $celebracao):
                        ?>
                        <tr>
                            <td><?php echo $celebracao->idCelebracao ?></td>
                            <td><?php foreach ($comunidade as $c) {
                                if ($c->idComunidade == $celebracao->idComunidadeFK) {
                                    echo $c->nomeComunidade;
                                    break;
                                }
                            } ?></t>
                            <td><?php echo $celebracao->semana ?>     <?php echo $celebracao->diaSemana ?></td>
                            <td><?php echo $celebracao->turno ?></td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center gap-1">
                                    <form action="<?php echo htmlspecialchars("celebracao.php") ?>" method="post"
                                        class="d-flex">
                                        <input type="hidden" name="id" value="<?php echo $celebracao->idCelebracao ?>">
                                        <button title="Editar" name="btnEditar" class="btn btn-primary btn-sm" type="submit"
                                            onclick="return confirm('Tem certeza que deseja editar a celebração?');">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </form>
                                    <?php if ($usuario->verificarNivelAcesso([1])): ?>
                                        <form action="<?php echo htmlspecialchars("celebracao.php") ?>" method="post"
                                            class="d-flex">
                                            <input type="hidden" name="id" value="<?php echo $celebracao->idCelebracao ?>">
                                            <button title="Deletar" name="btnDeletar" class="btn btn-danger btn-sm"
                                                type="submit"
                                                onclick="return confirm('Tem certeza que deseja deletar a celebração?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
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