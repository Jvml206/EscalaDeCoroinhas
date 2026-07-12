<?php
$nivelPermitidos = [1];
require_once "validaUser.php";
require_once "Includes/functions.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$c = new Comunidade();
$comunidades = $c->allOrder("ASC");

$cor = new Coroinha();
$coroinhas = $cor->allOrder("ASC");

$ce = new Celebracao();

$esc = new Escala();

if (isset($_POST['btnCadastrarEscalaMensal'])) {
    $dadosEscala = $_POST['escala'] ?? [];
    $dadosComunidade = $_POST['comunidade'] ?? [];

    if ($esc->salvarEscalaCompleta($dadosEscala, $dadosComunidade)) {
        echo "<script>alert('Escala salva com sucesso');</script>";
    } else {
        echo "Erro: " . $esc->getErro();
    }
}

if (isset($_POST['btnCadastrarEscalaSexta'])) {
    $dadosSexta = $_POST['escalaSexta'] ?? [];

    if ($esc->salvarEscalaSexta($dadosSexta)) {
        echo "<script>alert('Escala de sexta salva com sucesso');</script>";
    } else {
        echo "Erro: " . $esc->getErro();
    }
}

[$Escalas, $ComunidadesEscala] = $esc->montarMatrizEscalas();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/baseSite.css?v=<?php echo filemtime('CSS/baseSite.css'); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Escala Coroinhas</title>
</head>

<body>
    <?php require_once "_parts/_header2.php"; ?>
    <main class="container my-3">
        <h3 class="tituloPrincipal">Escala Coroinhas Com. N. S. de Fátima</h3>

        <form method="post" action="escala.php">
            <div class="table-responsive tabela-scroll">
                <table class="table tabela overflow-hidden dataTable">
                    <thead class="table-success">
                        <tr>
                            <th>Dia</th>
                            <th>Turno</th>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                            <th>5</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($semana = 1; $semana <= 5; $semana++): ?>
                            <tr>
                                <td rowspan="2" class="align-middle text-center">
                                    <?= $semana ?>º Domingo
                                </td>
                                <td class="align-middle text-center">Manhã</td>
                                <?php for ($pos = 1; $pos <= 5; $pos++): ?>
                                    <td>
                                        <select name="escala[<?= $semana ?>][Domingo][Manhã][<?= $pos ?>]" class="form-select">
                                            <?php renderOptionsCoroinha($coroinhas, coroinhaSelecionado($Escalas, $semana, 'Domingo', 'Manhã', $pos)) ?>
                                        </select>
                                    </td>
                                <?php endfor; ?>
                            </tr>
                            <tr>
                                <td class="align-middle text-center">Noite</td>
                                <?php for ($pos = 1; $pos <= 5; $pos++): ?>
                                    <td>
                                        <select name="escala[<?= $semana ?>][Domingo][Noite][<?= $pos ?>]" class="form-select">
                                            <?php renderOptionsCoroinha($coroinhas, coroinhaSelecionado($Escalas, $semana, 'Domingo', 'Noite', $pos)) ?>
                                        </select>
                                    </td>
                                <?php endfor; ?>
                            </tr>
                            <tr>
                                <td class="align-middle text-center"><?= $semana ?>º Segunda</td>
                                <td class="align-middle text-center">Noite</td>
                                <!-- coroinha -->
                                <?php for ($pos = 1; $pos <= 2; $pos++): ?>
                                    <td>
                                        <select name="escala[<?= $semana ?>][Segunda][Noite][<?= $pos ?>]" class="form-select">
                                            <?php renderOptionsCoroinha($coroinhas, coroinhaSelecionado($Escalas, $semana, 'Segunda', 'Noite', $pos), 'Coroinha') ?>
                                        </select>
                                    </td>
                                <?php endfor; ?>
                                <!-- comunidade -->
                                <td colspan="3">
                                    <select name="comunidade[<?= $semana ?>]" class="form-select align-middle text-center">
                                        <?php renderOptionsComunidade($comunidades, $ComunidadesEscala[$semana] ?? null) ?>
                                    </select>
                                </td>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>

                <div class="col-12 mt-3 mb-3 d-flex gap-2">
                    <button type="submit" name="btnCadastrarEscalaMensal" id="btnCadastrarEscalaMensal"
                        class="btn btn-outline-success">Salvar Escala Mensal</button>
                </div>
            </div>
        </form>

        <h3 class="tituloPrincipal">Escala 1° Sexta do Mês (Seminário)</h3>

        <form method="post" action="escala.php">
            <div class="table-responsive tabela-scroll">
                <table class="table tabela overflow-hidden dataTable">
                    <thead class="table-success">
                        <tr>
                            <th>Data</th>
                            <th>Responsável</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $dados = $ce->searchSexta(); ?>
                        <?php if (empty($dados)): ?>
                            <tr>
                                <td colspan="2" class="align-middle text-center">Nenhuma sexta-feira cadastrada.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($dados as $celebracao): ?>
                                <tr>
                                    <td class="align-middle text-center">
                                        <?= formatarData($celebracao['data']) ?>
                                    </td>
                                    <td>
                                        <select name="escalaSexta[<?= $celebracao['idCelebracao'] ?>][Noite][1]"
                                            class="form-select">
                                            <?php renderOptionsCoroinha($coroinhas, coroinhaSelecionado($Escalas, $celebracao['idCelebracao'], 'Sexta', 'Noite', 1)) ?>
                                        </select>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="col-12 mt-3 mb-3 d-flex gap-2">
                    <button type="submit" name="btnCadastrarEscalaSexta" id="btnCadastrarSexta"
                        class="btn btn-outline-success">Salvar Escala Sexta</button>
                </div>
            </div>
        </form>
    </main>

    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="JS/escala.js"></script>
</body>

</html>