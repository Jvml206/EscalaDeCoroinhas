<?php
require_once "validaUser.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});
$Comunidade = new Comunidade();

if (filter_has_var(INPUT_POST, "btnCadastrar")):

    $Comunidade->setNomeComunidade(filter_input(INPUT_POST, "nomeComunidade", FILTER_SANITIZE_STRING));
    $id = filter_input(INPUT_POST, 'id');

    if (empty($id)):
        //Tenta adicionar e exibe a mensagemao usuário
        if ($Comunidade->add()) {
            echo "<script>window.alert('Cadastro de Comunidade realizado com sucesso.');window.location.href=comunidade.php;</script>";
        } else {
            echo "<script>window.alert('Erro ao cadastrar a Comunidade.');window.open(document.referrer,'_self');</script>";
        }
    else:
        if ($Comunidade->update('idComunidade', $id)) {
            echo "<script>window.alert('Comunidade alterada com sucesso.'); 
            window.location.href='listaComunidades.php';</script>";
        } else {
            echo "<script> window.alert('Erro ao alterar a Comunidade.');
            window.open(document.referrer, '_self'); </script>";
        }
    endif;
elseif (filter_has_var(INPUT_POST, "btnDeletar")):
    $id = intval(filter_input(INPUT_POST, "id"));
    
    if ($Comunidade->delete("idComunidade", $id)) {
        header("location:listaComunidades.php");
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
    <title>Cadastro de Comunidade</title>
</head>

<body>
    <?php require_once "_parts/_header2.php"; ?>
    <main class="container my-3">

        <?php
        spl_autoload_register(function ($class) {
            require_once "Classes/{$class}.class.php";
        });

        if (filter_has_var(INPUT_POST, "btnEditar")) {
            $edtComunidades = new Comunidade;
            $id = intval(filter_input(INPUT_POST, "id"));
            $Comunidades = $edtComunidades->search("idComunidade", $id);
        }
        ?>

        <h2 class="tituloPrincipal text-center mb-4">Cadastro de Comunidade</h2>

        <form action="comunidade.php" method="post" class="row g-3 mt-3">

            <input type="hidden" value="<?php echo $Comunidades->idComunidade ?? null; ?>" name="id">

            <div class="col-md mb-3">
                <label for="nomeComunidade" class="form-label">Nome</label>
                <input type="text" name="nomeComunidade" id="nomeComunidade" placeholder="Digite o Nome da Comunidade" required
                    class="form-control" value="<?php echo $Comunidades->nomeComunidade ?? null; ?>">
            </div>

            <div class="col-12 mt-3 mb-3 d-flex gap-2">
                <button type="submit" name="btnCadastrar" id="btnCadastrar"
                    class="btn btn-outline-success">Salvar</button>
                <a href="listaComunidades.php" role="button" class="btn btn-outline-danger">Cancelar</a>
            </div>
        </form>
    </main>

    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>