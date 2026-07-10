<?php
$nivelPermitidos = [1, 2];
require_once "validaUser.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});
$Coroinha = new Coroinha();

if (filter_has_var(INPUT_POST, "btnCadastrar")):

    $fotoAntiga = filter_input(INPUT_POST, 'fotoAntiga');
    $Coroinha->setFoto($fotoAntiga);

    $Coroinha->setNomeCoroinha(filter_input(INPUT_POST, "nomeCoroinha", FILTER_SANITIZE_STRING));
    $Coroinha->setNivel(filter_input(INPUT_POST, "nivel", FILTER_SANITIZE_STRING));
    $Coroinha->setStatus(filter_input(INPUT_POST, "status", FILTER_SANITIZE_STRING));
    $Coroinha->setPreferenciaTurno(filter_input(INPUT_POST, "preferenciaTurno", FILTER_SANITIZE_STRING));
    $Coroinha->setPreferenciaDomingo(filter_input(INPUT_POST, "preferenciaDomingo", FILTER_SANITIZE_STRING));
    $Coroinha->setPodeSegunda(filter_input(INPUT_POST, "podeSegunda", FILTER_SANITIZE_STRING));
    $Coroinha->setNumeroServindo(0);
    
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($extensao, $permitidas)) {
            $nomeFoto = uniqid("coroinha_") . "." . $extensao;
            $destino = "Images/Coroinha/" . $nomeFoto;
            $caminhoAntigo = "Images/Coroinha/" . $fotoAntiga;

            if (!empty($fotoAntiga) && is_file($caminhoAntigo)) {
                unlink($caminhoAntigo); // Apaga a foto antiga
            }
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                $Coroinha->setFoto($nomeFoto);
            }
        }
    }


    $id = filter_input(INPUT_POST, 'id');
    if (empty($id)):
        //Tenta adicionar e exibe a mensagemao usuário
        if ($Coroinha->add()) {
            echo "<script>window.alert('Cadastro de Coroinha realizado com sucesso.');window.location.href=coroinha.php;</script>";
        } else {
            echo "<script>window.alert('Erro ao cadastrar a Coroinha.');window.open(document.referrer,'_self');</script>";
        }
    else:
        if ($Coroinha->update('idCoroinha', $id)) {
            echo "<script>window.alert('Coroinha alterado com sucesso.'); 
            window.location.href='listaCoroinhas.php';</script>";
        } else {
            echo "<script> window.alert('Erro ao alterar o Coroinha.');
            window.open(document.referrer, '_self'); </script>";
        }
    endif;
elseif (filter_has_var(INPUT_POST, "btnDeletar")):
    $id = intval(filter_input(INPUT_POST, "id"));
    $delCoroinha = $Coroinha->search("idCoroinha", $id);

    $fotoApagar = "Images/Coroinha/" . $delCoroinha->foto;
    if (!empty($delCoroinha->foto) && is_file($fotoApagar)) {
        unlink($fotoApagar);
    }

    if ($Coroinha->delete("idCoroinha", $id)) {
        header("location:listaCoroinhas.php");
    } else {
        echo "<script>window.alert('Erro ao excluir'); window(document.referrer, '_self');</script>";
    }
elseif (filter_has_var(INPUT_POST, "btnStatus")):
    $id = intval(filter_input(INPUT_POST, "id"));
    $Coroinha->updateStatus($id);
    echo "<script>alert('Status alterado com sucesso'); window.open(document.referrer, '_self');</script>";
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
    <title>Cadastro de Coroinha</title>
</head>

<body>
    <?php require_once "_parts/_header2.php"; ?>
    <main class="container my-3">

        <?php
        if (filter_has_var(INPUT_POST, "btnEditar")) {
            $edtCoroinhas = new Coroinha;
            $id = intval(filter_input(INPUT_POST, "id"));
            $Coroinhas = $edtCoroinhas->search("idCoroinha", $id);
        }
        ?>

        <h2 class="tituloPrincipal text-center mb-4">Cadastro de Coroinha</h2>

        <form action="coroinha.php" method="post" enctype="multipart/form-data" class="row g-3 mt-3">

            <input type="hidden" value="<?php echo $Coroinhas->idCoroinha ?? null; ?>" name="id">
            <input type="hidden" name="fotoAntiga" value="<?php echo $Coroinhas->foto ?? ''; ?>">

            <div class="col-md-6 mb-3">
                <label for="nomeCoroinha" class="form-label">Nome</label>
                <input type="text" name="nomeCoroinha" id="nomeCoroinha" placeholder="Digite o Nome do Coroinha" required
                    class="form-control" value="<?php echo $Coroinhas->nomeCoroinha ?? null; ?>">
            </div>

            <div class="col-6">
                <label for="nivel" class="form-label">Nível</label>
                <select name="nivel" class="form-select" aria-label="Default select example" id="nivel" required>
                    <option disabled <?= (!isset($Coroinhas->nivel)) ? 'selected' : '' ?>>Selecione o Nível
                    </option>
                    <option value="Nível 1" <?= (isset($Coroinhas->nivel) && $Coroinhas->nivel == 'Nível 1') ? 'selected' : '' ?>>Nível 1</option>
                    <option value="Nível 2" <?= (isset($Coroinhas->nivel) && $Coroinhas->nivel == 'Nível 2') ? 'selected' : '' ?>>Nível 2</option>
                    <option value="Acólito" <?= (isset($Coroinhas->nivel) && $Coroinhas->nivel == 'Acólito') ? 'selected' : '' ?>>Acólito</option>
                </select>
            </div>

            <div class="col-6">
                <label for="status" class="form-label">Status</label>
                <select name="status" class="form-select" aria-label="Default select example" id="status" required>
                    <option disabled <?= (!isset($Coroinhas->status)) ? 'selected' : '' ?>>Status</option>
                    <option value="Servindo" <?= (isset($Coroinhas->status) && $Coroinhas->status == 'Servindo') ? 'selected' : '' ?>>Servindo</option>
                    <option value="Ex-coroinha" <?= (isset($Coroinhas->status) && $Coroinhas->status == 'Ex-coroinha') ? 'selected' : '' ?>>Ex-coroinha</option>
                </select>
            </div>

            <div class="col-6">
                <label for="preferenciaTurno" class="form-label">Turno de Preferência</label>
                <select name="preferenciaTurno" class="form-select" aria-label="Default select example"
                    id="preferenciaTurno" required>
                    <option disabled <?= (!isset($Coroinhas->preferenciaTurno)) ? 'selected' : '' ?>>Selecione o Turno de
                        Preferência
                    </option>
                    <option value="Manhã" <?= (isset($Coroinhas->preferenciaTurno) && $Coroinhas->preferenciaTurno == 'Manhã') ? 'selected' : '' ?>>Manhã</option>
                    <option value="Noite" <?= (isset($Coroinhas->preferenciaTurno) && $Coroinhas->preferenciaTurno == 'Noite') ? 'selected' : '' ?>>Noite</option>
                    <option value="Sem Preferência" <?= (isset($Coroinhas->preferenciaTurno) && $Coroinhas->preferenciaTurno == 'Sem Preferência') ? 'selected' : '' ?>>Sem Preferência</option>
                </select>
            </div>

            <div class="col-md-12">
                <label for="preferenciaDomingo" class="form-label">Preferências de Domingo</label>
                <input type="text" name="preferenciaDomingo" id="preferenciaDomingo"
                    placeholder="Digite as preferências de domingo" required class="form-control"
                    value="<?php echo $Coroinhas->preferenciaDomingo ?? null; ?>">
            </div>

            <div class="col-6">
                <label for="podeSegunda" class="form-label">Pode Servir Segunda</label>
                <select name="podeSegunda" class="form-select" aria-label="Default select example" id="podeSegunda"
                    required>
                    <option disabled <?= (!isset($Coroinhas->podeSegunda)) ? 'selected' : '' ?>>Selecione se pode servir segunda</option>
                    <option value="Sim" <?= (isset($Coroinhas->podeSegunda) && $Coroinhas->podeSegunda == 'Sim') ? 'selected' : '' ?>>Sim</option>
                    <option value="Não" <?= (isset($Coroinhas->podeSegunda) && $Coroinhas->podeSegunda == 'Não') ? 'selected' : '' ?>>Não</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="foto" class="form-label">Foto do Coroinha</label>
                <input type="file" name="foto" id="foto" accept="image/*" class="form-control">
                <?php if (!empty($Coroinhas->foto)): ?>
                    <img src="Images/Coroinha/<?php echo $Coroinhas->foto; ?>" alt="Foto do Coroinha"
                        class="mt-2 foto-coroinha-cadastro">
                <?php endif; ?>
            </div>

            <div class="col-12 mt-3 mb-3 d-flex gap-2">
                <button type="submit" name="btnCadastrar" id="btnCadastrar"
                    class="btn btn-outline-success">Salvar</button>
                <a href="listaCoroinhas.php" role="button" class="btn btn-outline-danger">Cancelar</a>
            </div>
        </form>
    </main>

    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>