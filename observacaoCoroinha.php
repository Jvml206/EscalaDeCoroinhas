<?php
$nivelPermitidos = [1, 2];
require_once "validaUser.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$Observacao = new Observacao();

$idCoroinhaFK = filter_input(INPUT_GET, 'idCoroinhaFK', FILTER_VALIDATE_INT);

if (!$idCoroinhaFK) {
    header("Location: infosCoroinhas.php");
    exit;
}

$coro = new Coroinha();
$coroinha = $coro->search("idCoroinha", $idCoroinhaFK);

if (filter_has_var(INPUT_POST, "btnCadastrar")):

    $Observacao->setIdCoroinhaFK($idCoroinhaFK);
    $Observacao->setObservacao(filter_input(INPUT_POST, "observacao", FILTER_SANITIZE_STRING));
    $id = filter_input(INPUT_POST, 'id');

    if (empty($id)):
        if ($Observacao->add()) {
            header("Location: observacaoCoroinha.php?idCoroinhaFK=$idCoroinhaFK");
            exit;
        } else {
            echo "<script>window.alert('Erro ao cadastrar a Observação.');window.open(document.referrer,'_self');</script>";
        }
    else:
        if ($Observacao->update('idObservacao', $id)) {
            header("Location: observacaoCoroinha.php?idCoroinhaFK=$idCoroinhaFK");
            exit;
        } else {
            echo "<script> window.alert('Erro ao alterar a Observação.');
            window.open(document.referrer, '_self'); </script>";
        }
    endif;
elseif (filter_has_var(INPUT_POST, "btnDeletar")):
    $id = intval(filter_input(INPUT_POST, "id"));

    $obsStatus = $Observacao->search("idObservacao", $id);
    if ($obsStatus->status == "Corrigida") {
        echo "<script>window.alert('Não é possível excluir uma observação corrigida.'); window.open(document.referrer, '_self');</script>";
        exit;
    }

    if ($Observacao->delete("idObservacao", $id)) {
        header("Location: observacaoCoroinha.php?idCoroinhaFK=$idCoroinhaFK");
        exit;
    } else {
        echo "<script>window.alert('Erro ao excluir'); window.open(document.referrer, '_self');</script>";
    }
elseif (filter_has_var(INPUT_POST, "btnStatus")):
    $id = intval(filter_input(INPUT_POST, "id"));

    $obsStatus = $Observacao->search("idObservacao", $id);
    if ($obsStatus->status == "Corrigida") {
        echo "<script>window.alert('Não é possível alterar o status de uma observação corrigida.'); window.open(document.referrer, '_self');</script>";
        exit;
    }

    $Observacao->setStatus("Corrigida");
    if ($Observacao->updateStatus('idObservacao', $id)) {
        header("Location: observacaoCoroinha.php?idCoroinhaFK=$idCoroinhaFK");
        exit;
    } else {
        echo "<script>window.alert('Erro ao alterar o status'); window.open(document.referrer, '_self');</script>";
    }
endif;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/tabelas.css?v=<?php echo filemtime('CSS/tabelas.css'); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Observações de <?php echo $coroinha->nomeCoroinha ?? null; ?></title>
</head>

<body>
    <?php require_once "_parts/_header2.php"; ?>
    <main class="container my-3">

        <?php if (filter_has_var(INPUT_POST, "btnEditar")) {
            $id = intval(filter_input(INPUT_POST, "id"));
            $Observacoes = $Observacao->search("idObservacao", $id);
            if ($Observacoes->status == "Corrigida") {
                echo "<script>window.alert('Não é possível editar uma observação corrigida.'); window.open(document.referrer, '_self');</script>";
                exit;
            }
        } ?>

        <h2 class="tituloPrincipal text-center mb-4">Observações de <?php echo $coroinha->nomeCoroinha ?? null; ?></h2>

        <form action="observacaoCoroinha.php?idCoroinhaFK=<?= $idCoroinhaFK ?>" method="post" class="row g-3 mt-3">

            <input type="hidden" name="id" value="<?= $Observacoes->idObservacao ?? '' ?>">

            <div class="col-md mb-3">
                <label for="observacao" class="form-label">Observação</label>
                <input type="text" name="observacao" id="observacao" placeholder="Digite a Observação" required
                    class="form-control" value="<?php echo $Observacoes->observacao ?? null; ?>">
            </div>

            <div class="col-12 mt-3 mb-3 d-flex gap-2">
                <button type="submit" name="btnCadastrar" id="btnCadastrar"
                    class="btn btn-outline-success">Salvar</button>
                <a href="infosCoroinhas.php" role="button" class="btn btn-outline-danger">Voltar</a>
            </div>
        </form>

        <div class="table-responsive tabela-scroll">
            <table class="tableaCe">
                <thead>
                    <tr>
                        <th class="text-center">Observação</th>
                        <th class="text-center">Status (Em observação/Corrigida)</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $obs = $Observacao->allObservacoesCoroinha($idCoroinhaFK);
                    foreach ($obs as $observa):
                        ?>
                        <tr>
                            <td class="observacaoDado"><?php echo $observa->observacao ?></td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center gap-1">
                                    <form action="observacaoCoroinha.php?idCoroinhaFK=<?php echo $idCoroinhaFK; ?>"
                                        method="post" class="d-flex">
                                        <input type="hidden" name="id" value="<?php echo $observa->idObservacao ?>">
                                        <button title="Editar" name="btnStatus" class="btn btn-primary btn-sm" type="submit"
                                            <?php echo $observa->status == 'Em observação' ? '' : 'disabled'; ?>>
                                            <?php echo $observa->status == 'Em observação' ? 'Em observação' : 'Corrigida'; ?>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center gap-1">
                                    <form action="observacaoCoroinha.php?idCoroinhaFK=<?php echo $idCoroinhaFK; ?>"
                                        method="post" class="d-flex">
                                        <input type="hidden" name="id" value="<?php echo $observa->idObservacao ?>">
                                        <button title="Editar" name="btnEditar" class="btn btn-primary btn-sm" type="submit"
                                            <?php echo $observa->status == 'Em observação' ? '' : 'disabled'; ?>>
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </form>
                                    <?php if ($usuario->verificarNivelAcesso([1])): ?>
                                        <form action="observacaoCoroinha.php?idCoroinhaFK=<?php echo $idCoroinhaFK; ?>"
                                            method="post" class="d-flex">
                                            <input type="hidden" name="id" value="<?php echo $observa->idObservacao ?>">
                                            <button title="Deletar" name="btnDeletar" class="btn btn-danger btn-sm"
                                                type="submit"
                                                onclick="return confirm('Tem certeza que deseja deletar a observação?')" <?php echo $observa->status == 'Em observação' ? '' : 'disabled'; ?>>
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
</body>

</html>