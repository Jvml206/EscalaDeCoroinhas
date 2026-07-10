<?php
$nivelPermitidos = [1];
require_once "validaUser.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});
$usuario = new Usuario();

if (filter_has_var(INPUT_POST, "btnCadastrar")):

    $usuario->setNomeUsuario(filter_input(INPUT_POST, "nomeUsuario", FILTER_SANITIZE_STRING));
    $usuario->setEmailUsuario(filter_input(INPUT_POST, "emailUsuario", FILTER_SANITIZE_STRING));
    $usuario->setSenhaUsuario("senhaTemporaria");
    $usuario->setNivelAcessoUsuario(filter_input(INPUT_POST, "nivelAcessoUsuario", FILTER_SANITIZE_NUMBER_INT));

    $id = filter_input(INPUT_POST, 'id');
    if (empty($id)):
        if ($usuario->add()) {
            echo "<script>window.alert('Cadastro de Usuário realizado com sucesso.');window.location.href=usuario.php;</script>";
        } else {
            echo "<script>window.alert('Erro ao cadastrar o Usuário.');window.open(document.referrer,'_self');</script>";
        }
    else:
        if ($usuario->update('idUsuario', $id)) {
        } else {
            echo "<script> window.alert('Erro ao alterar o Usuário.');
            window.open(document.referrer, '_self'); </script>";
        }
    endif;
elseif (filter_has_var(INPUT_POST, "btnDeletar")):
    $id = intval(filter_input(INPUT_POST, "id"));
    $delUsuario = $usuario->search("idUsuario", $id);

    if ($usuario->delete("idUsuario", $id)) {
        header("location:usuario.php");
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
    <link rel="stylesheet" href="CSS/tabelas.css?v=<?php echo filemtime('CSS/tabelas.css'); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Cadastro de Usuário</title>
</head>

<body>
    <?php require_once "_parts/_header2.php"; ?>

    <main class="container my-3">
        <?php
        if (filter_has_var(INPUT_POST, "btnEditar")) {
            $edtUsuarios = new Usuario;
            $id = intval(filter_input(INPUT_POST, "id"));
            $Usuarios = $edtUsuarios->search("idUsuario", $id);
        }
        ?>

        <h2 class="tituloPrincipal text-center mb-4">Usuários</h2>

        <form action="usuario.php" method="post" class="row g-3 mt-3">

            <input type="hidden" value="<?php echo $Usuarios->idUsuario ?? null; ?>" name="id">

            <div class="col-md-4 mb-3">
                <label for="nomeUsuario" class="form-label">Nome</label>
                <input type="text" name="nomeUsuario" id="nomeUsuario" placeholder="Digite o Nome do Usuário" required
                    class="form-control" value="<?php echo $Usuarios->nomeUsuario ?? null; ?>">
            </div>

            <div class="col-md-4 mb-3">
                <label for="emailUsuario" class="form-label">Email</label>
                <input type="email" name="emailUsuario" id="emailUsuario" placeholder="Digite o Email do Usuário"
                    required class="form-control" value="<?php echo $Usuarios->emailUsuario ?? null; ?>">
            </div>

            <div class="col-4">
                <label for="nivelDeAcessoUsuario" class="form-label">Nível de Acesso</label>
                <select name="nivelAcessoUsuario" class="form-select" aria-label="Default select example"
                    id="nivelDeAcessoUsuario" required>
                    <option disabled <?= (!isset($Usuarios->nivelAcessoUsuario)) ? 'selected' : '' ?>>Selecione o Nível
                    </option>
                    <option value="1" <?= (isset($Usuarios->nivelAcessoUsuario) && $Usuarios->nivelAcessoUsuario == 1) ? 'selected' : '' ?>>Administrador</option>
                    <option value="2" <?= (isset($Usuarios->nivelAcessoUsuario) && $Usuarios->nivelAcessoUsuario == 2) ? 'selected' : '' ?>>Coordenador</option>
                    <option value="3" <?= (isset($Usuarios->nivelAcessoUsuario) && $Usuarios->nivelAcessoUsuario == 3) ? 'selected' : '' ?>>Coordenador de outra comunidade</option>
                </select>
            </div>

            <div class="col-12 mt-3 mb-3 d-flex gap-2">
                <button type="submit" name="btnCadastrar" id="btnCadastrar"
                    class="btn btn-outline-success">Salvar</button>
            </div>
        </form>
        
        <div class="table-responsive tabela-scroll">
            <table class="tableaCe">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="text-center">Nome</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Nível de Acesso</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $usu = $usuario->all();
                    foreach ($usu as $users):
                        ?>
                        <tr>
                            <td><?php echo $users->idUsuario ?></td>
                            <td><?php echo $users->nomeUsuario ?></td>
                            <td><?php echo $users->emailUsuario ?></td>
                            <td><?php echo $users->nivelAcessoUsuario ?></td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center gap-1">
                                    <form action="<?php echo htmlspecialchars("usuario.php") ?>" method="post"
                                        class="d-flex">
                                        <input type="hidden" name="id" value="<?php echo $users->idUsuario ?>">
                                        <button title="Editar" name="btnEditar" class="btn btn-primary btn-sm"
                                            type="submit">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </form>

                                    <form action="<?php echo htmlspecialchars("usuario.php") ?>" method="post"
                                        class="d-flex">
                                        <input type="hidden" name="id" value="<?php echo $users->idUsuario ?>">
                                        <button title="Deletar" name="btnDeletar" class="btn btn-danger btn-sm"
                                            type="submit"
                                            onclick="return confirm('Tem certeza que deseja deletar o usuário?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
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