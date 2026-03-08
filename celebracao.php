<?php
require_once "validaUser.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});
$Celebracao = new Celebracao();

if (filter_has_var(INPUT_POST, "btnCadastrar")):

    $Celebracao->setSemana(filter_input(INPUT_POST, "semana", FILTER_SANITIZE_STRING));
    $Celebracao->setDiaSemana(filter_input(INPUT_POST, "diaSemana", FILTER_SANITIZE_STRING));
    $Celebracao->setTurno(filter_input(INPUT_POST, "turno", FILTER_SANITIZE_STRING));
    $Celebracao->setIdComunidadeFK(filter_input(INPUT_POST, "idComunidadeFK", FILTER_SANITIZE_STRING));

    $id = filter_input(INPUT_POST, 'id');
    if (empty($id)):
        //Tenta adicionar e exibe a mensagemao usuário
        if ($Celebracao->add()) {
            echo "<script>window.alert('Cadastro de Celebração realizado com sucesso.');window.location.href=celebracao.php;</script>";
        } else {
            echo "<script>window.alert('Erro ao cadastrar a Celebração.');window.open(document.referrer,'_self');</script>";
        }
    else:
        if ($Celebracao->update('idCelebracao', $id)) {
            echo "<script>window.alert('Celebração alterada com sucesso.'); 
            window.location.href='listaCelebracoes.php';</script>";
        } else {
            echo "<script> window.alert('Erro ao alterar o Celebração.');
            window.open(document.referrer, '_self'); </script>";
        }
    endif;
elseif (filter_has_var(INPUT_POST, "btnDeletar")):
    $id = intval(filter_input(INPUT_POST, "id"));

    if ($Celebracao->delete("idCelebracao", $id)) {
        header("location:listaCelebracoes.php");
    } else {
        echo "<script>window.alert('Erro ao excluir'); window(document.referrer, '_self');</script>";
    }
endif;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/baseSite.css?v=<?php echo filemtime('CSS/baseSite.css'); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Cadastro de Celebração</title>
</head>

<body>
    <?php require_once "_parts/_header2.php"; ?>
    <main class="container my-3">

        <?php
        spl_autoload_register(function ($class) {
            require_once "Classes/{$class}.class.php";
        });

        $Comunidade = new Comunidade;
        $comunidade = $Comunidade->all();
        if (filter_has_var(INPUT_POST, "btnEditar")) {
            $edtCelebracoes = new Celebracao;
            $id = intval(filter_input(INPUT_POST, "id"));
            $Celebracoes = $edtCelebracoes->search("idCelebracao", $id);
        }
        ?>

        <h2 class="tituloPrincipal text-center mb-4">Cadastro de Celebração</h2>

        <form action="celebracao.php" method="post" class="row g-3 mt-3">

            <input type="hidden" value="<?php echo $Celebracoes->idCelebracao ?? null; ?>" name="id">

            <div class="col-6">
                <label for="semana" class="form-label">Semana</label>
                <select name="semana" class="form-select" aria-label="Default select example" id="semana" required>
                    <option disabled <?= (!isset($Celebracoes->semana)) ? 'selected' : '' ?>>Selecione a semana
                    </option>
                    <option value="1°" <?= (isset($Celebracoes->semana) && $Celebracoes->semana == '1°') ? 'selected' : '' ?>>1°</option>
                    <option value="2°" <?= (isset($Celebracoes->semana) && $Celebracoes->semana == '2°') ? 'selected' : '' ?>>2°</option>
                    <option value="3°" <?= (isset($Celebracoes->semana) && $Celebracoes->semana == '3°') ? 'selected' : '' ?>>3°</option>
                    <option value="4°" <?= (isset($Celebracoes->semana) && $Celebracoes->semana == '4°') ? 'selected' : '' ?>>4°</option>
                    <option value="5°" <?= (isset($Celebracoes->semana) && $Celebracoes->semana == '5°') ? 'selected' : '' ?>>5°</option>
                </select>
            </div>

            <div class="col-6">
                <label for="diaSemana" class="form-label">Dia da Semana</label>
                <select name="diaSemana" class="form-select" aria-label="Default select example" id="diaSemana"
                    required>
                    <option disabled <?= (!isset($Celebracoes->diaSemana)) ? 'selected' : '' ?>>Dia da Semana</option>
                    <option value="Domingo" <?= (isset($Celebracoes->diaSemana) && $Celebracoes->diaSemana == 'Domingo') ? 'selected' : '' ?>>Domingo</option>
                    <option value="Segunda" <?= (isset($Celebracoes->diaSemana) && $Celebracoes->diaSemana == 'Segunda') ? 'selected' : '' ?>>Segunda</option>
                </select>
            </div>

            <div class="col-6">
                <label for="turno" class="form-label">Turno</label>
                <select name="turno" class="form-select" aria-label="Default select example" id="turno" required>
                    <option disabled <?= (!isset($Celebracoes->turno)) ? 'selected' : '' ?>>Selecione o Turno
                    </option>
                    <option value="Manhã" <?= (isset($Celebracoes->turno) && $Celebracoes->turno == 'Manhã') ? 'selected' : '' ?>>Manhã</option>
                    <option value="Noite" <?= (isset($Celebracoes->turno) && $Celebracoes->turno == 'Noite') ? 'selected' : '' ?>>Noite</option>
                </select>
            </div>

            <div class="col-6">
                <label for="idComunidadeFK" class="form-label">Comunidade</label>
                <select name="idComunidadeFK" class="form-select" id="idComunidadeFK" required>
                    <option disabled <?= (!isset($Celebracoes->idComunidadeFK)) ? 'selected' : '' ?>>Selecione a comunidade</option>
                    <?php foreach ($comunidade as $c): ?>
                        <option value="<?= $c->idComunidade ?>" <?= (!empty($Celebracoes) && intval($Celebracoes->idComunidadeFK) === intval($c->idComunidade)) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c->nomeComunidade) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 mt-3 mb-3 d-flex gap-2">
                <button type="submit" name="btnCadastrar" id="btnCadastrar"
                    class="btn btn-outline-success">Salvar</button>
                <a href="listaCelebracoes.php" role="button" class="btn btn-outline-danger">Cancelar</a>
            </div>
        </form>
    </main>

    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>