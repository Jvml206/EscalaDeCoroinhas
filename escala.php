<?php
require_once "validaUser.php";

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

$db = Database::getInstance()->getConnection();
if (isset($_POST['btnCadastrar'])) {
    try {
        $db->beginTransaction();

        foreach ($_POST['escala'] as $semana => $dias) {



            // Agora salva os coroinhas normalmente
            foreach ($dias as $dia => $turnos) {
                foreach ($turnos as $turno => $posicoes) {

                    /* BUSCAR CELEBRAÇÃO */
                    $sqlCelebracao = $db->prepare("
                        SELECT idCelebracao 
                        FROM Celebracao
                        WHERE semana = :semana
                        AND diaSemana = :dia
                        AND turno = :turno
                    ");
                    $sqlCelebracao->execute([
                        ":semana" => $semana . '°',
                        ":dia" => $dia,
                        ":turno" => $turno
                    ]);
                    $celebracao = $sqlCelebracao->fetch(PDO::FETCH_OBJ);
                    if (!$celebracao)
                        continue;

                    $idCelebracao = $celebracao->idCelebracao;

                    $comunidadeSemana = $_POST['comunidade'][$semana] ?? null;

                    /* APAGA ESCALA ANTIGA */
                    $del = $db->prepare("DELETE FROM Escala WHERE idCelebracaoFK = :id");
                    $del->execute([":id" => $idCelebracao]);

                    /* SALVAR COMUNIDADE */
                    if ($comunidadeSemana != "") {
                        // Escolhe a posição da comunidade na Segunda — por exemplo, posicao 1
                        $posicao = 3; // ou ajuste conforme necessário

                        // Insere comunidade na tabela Escala
                        $sqlCom = $db->prepare("INSERT INTO Escala (idCelebracaoFK, posicao, idComunidadeFK) VALUES (:idCelebracao, :pos, :com)");
                        $sqlCom->execute([
                            ":idCelebracao" => $idCelebracao,
                            ":pos" => $posicao,
                            ":com" => $comunidadeSemana
                        ]);
                    }

                    /* SALVAR COROINHAS */
                    foreach ($posicoes as $pos => $idCoroinha) {
                        if ($idCoroinha === "" || $idCoroinha === null || $idCoroinha === 0)
                            continue;
                        $sql = $db->prepare("
                            INSERT INTO Escala
                            (idCelebracaoFK,idCoroinhaFK,posicao)
                            VALUES (:celebracao,:coroinha,:posicao)
                        ");
                        $sql->execute([
                            ":celebracao" => $idCelebracao,
                            ":coroinha" => $idCoroinha,
                            ":posicao" => $pos
                        ]);
                    }
                }
            }
        }

        /* ===== ATUALIZAR numeroServindo ===== */
        $db->exec("UPDATE Coroinha SET numeroServindo = 0");
        $db->exec("UPDATE Coroinha c SET numeroServindo = (SELECT COUNT(*) FROM Escala e WHERE e.idCoroinhaFK = c.idCoroinha)");

        $db->commit();
        echo "<script>alert('Escala salva com sucesso');</script>";

    } catch (Exception $e) {
        $db->rollBack();
        echo "Erro: " . $e->getMessage();
    }
}

$Escalas = [];
$ComunidadesEscala = [];
$sql = $db->query("
SELECT 
    e.idCelebracaoFK,
    e.idCoroinhaFK,
    e.posicao,
    c.semana,
    c.diaSemana,
    c.turno,
    c.idComunidadeFK
FROM Escala e
JOIN Celebracao c 
ON c.idCelebracao = e.idCelebracaoFK
");
$result = $sql->fetchAll(PDO::FETCH_OBJ);
foreach ($result as $r) {
    $semana = str_replace('°', '', $r->semana);
    $Escalas[$semana][$r->diaSemana][$r->turno][$r->posicao] = $r->idCoroinhaFK;
    $ComunidadesEscala[$semana] = $r->idComunidadeFK;
}
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
                                        <option value="" class="align-middle text-center">--</option>
                                        <?php $selecionado = $Escalas[$semana]['Domingo']['Manhã'][$pos] ?? null; ?>
                                        <?php foreach ($coroinhas as $c): ?>
                                            <option value="<?= $c->idCoroinha ?>" data-nivel="<?= $c->nivel ?>"
                                                <?= ($selecionado == $c->idCoroinha) ? 'selected' : '' ?>
                                                class="align-middle text-center">
                                                <?= $c->nomeCoroinha ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            <?php endfor; ?>
                        </tr>
                        <tr>
                            <td class="align-middle text-center">Noite</td>
                            <?php for ($pos = 1; $pos <= 5; $pos++): ?>
                                <td>
                                    <select name="escala[<?= $semana ?>][Domingo][Noite][<?= $pos ?>]" class="form-select">
                                        <option value="" class="align-middle text-center">--</option>
                                        <?php $selecionado = $Escalas[$semana]['Domingo']['Noite'][$pos] ?? null; ?>
                                        <?php foreach ($coroinhas as $c): ?>
                                            <option value="<?= $c->idCoroinha ?>" data-nivel="<?= $c->nivel ?>"
                                                <?= ($selecionado == $c->idCoroinha) ? 'selected' : '' ?>
                                                class="align-middle text-center">
                                                <?= $c->nomeCoroinha ?>
                                            </option>
                                        <?php endforeach; ?>
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
                                        <option value="" class="align-middle text-center">Coroinha</option>
                                        <?php
                                        $selecionado = $Escalas[$semana]['Segunda']['Noite'][$pos] ?? null;
                                        ?>
                                        <?php foreach ($coroinhas as $c): ?>
                                            <option value="<?= $c->idCoroinha ?>" data-nivel="<?= $c->nivel ?>"
                                                <?= ($selecionado == $c->idCoroinha) ? 'selected' : '' ?>
                                                class="align-middle text-center">
                                                <?= $c->nomeCoroinha ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            <?php endfor; ?>
                            <!-- comunidade -->
                            <td colspan="3">
                                <select name="comunidade[<?= $semana ?>]" class="form-select align-middle text-center">
                                    <?php $comSel = $ComunidadesEscala[$semana] ?? null; ?>
                                    <option value="">Selecionar comunidade</option>
                                    <?php foreach ($comunidades as $com): ?>
                                        <option value="<?= $com->idComunidade ?>" class="align-middle text-center"
                                            <?= ($comSel == $com->idComunidade) ? 'selected' : '' ?>>
                                            <?= $com->nomeComunidade ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>

            <div class="col-12 mt-3 mb-3 d-flex gap-2">
                <button type="submit" name="btnCadastrar" id="btnCadastrar"
                    class="btn btn-outline-success">Salvar</button>
            </div>
            </table>
        </form>
    </main>

    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="JS/escala.js"></script>
</body>

</html>