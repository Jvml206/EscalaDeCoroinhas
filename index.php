<?php
spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$c = new Comunidade();
$comunidades = $c->all();
$cor = new Coroinha();
$coroinhas = $cor->all();
$esc = new Escala();
$escala = $esc->all();

$db = Database::getInstance()->getConnection();

$sql = $db->query("
    SELECT e.*, 
           c.semana, c.diaSemana, c.turno, 
           co.nivel, co.nomeCoroinha,
           com.nomeComunidade
    FROM Escala e
    JOIN Celebracao c ON c.idCelebracao = e.idCelebracaoFK
    LEFT JOIN Coroinha co ON co.idCoroinha = e.idCoroinhaFK
    LEFT JOIN Comunidade com ON com.idComunidade = e.idComunidadeFK
");
$result = $sql->fetchAll(PDO::FETCH_OBJ);

$Escalas = [];
foreach ($result as $r) {
    $semana = str_replace('°', '', $r->semana);
    $Escalas[$semana][$r->diaSemana][$r->turno][$r->posicao] = [
        'coroinha' => $r->idCoroinhaFK,
        'comunidade' => $r->idComunidadeFK
    ];
}

$EscalasCom = [];
foreach ($result as $r) {
    $semana = str_replace('°', '', $r->semana);
    $EscalasCom[$semana][$r->diaSemana][$r->turno][$r->posicao] = [
        'coroinha' => $r->nomeCoroinha ?? null,
        'comunidade' => $r->nomeComunidade ?? null
    ];
}

$coroinhasMap = [];
foreach ($coroinhas as $c) {
    $coroinhasMap[$c->idCoroinha] = $c;
}

$comunidadesMap = [];
foreach ($comunidades as $com) {
    $comunidadesMap[$com->idComunidade] = $com;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/index.css?v=<?php echo filemtime('CSS/index.css'); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Escala Coroinhas</title>
</head>

<body>
    <?php require_once "_parts/_header.php"; ?>
    <main class="container my-3">
        <h1 class="h1-index">Escala Coroinhas Com. N. S. de Fátima</h1>

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
            <tbody class="align-middle text-center">
                <?php for ($semana = 1; $semana <= 5; $semana++): ?>
                    <tr>
                        <td rowspan="2">
                            <?= $semana ?>º Domingo
                        </td>
                        <td>Manhã</td>
                        <?php for ($pos = 1; $pos <= 5; $pos++): ?>
                            <td
                                data-nivel="<?= $coroinhasMap[$Escalas[$semana]['Domingo']['Manhã'][$pos]['coroinha']]->nivel ?>">
                                <?= isset($Escalas[$semana]['Domingo']['Manhã'][$pos]['coroinha'])
                                    ? $coroinhasMap[$Escalas[$semana]['Domingo']['Manhã'][$pos]['coroinha']]->nomeCoroinha
                                    : '--' ?>
                            </td>
                        <?php endfor; ?>
                    </tr>
                    <tr>
                        <td>Noite</td>
                        <?php for ($pos = 1; $pos <= 5; $pos++): ?>
                            <td
                                data-nivel="<?= $coroinhasMap[$Escalas[$semana]['Domingo']['Noite'][$pos]['coroinha']]->nivel ?>">
                                <?= isset($Escalas[$semana]['Domingo']['Noite'][$pos]['coroinha'])
                                    ? $coroinhasMap[$Escalas[$semana]['Domingo']['Noite'][$pos]['coroinha']]->nomeCoroinha
                                    : '--' ?>
                            </td>
                        <?php endfor; ?>
                    </tr>
                    <tr>
                        <td><?= $semana ?>º Segunda</td>
                        <td>Noite</td>
                        <!-- coroinha -->
                        <?php for ($pos = 1; $pos <= 2; $pos++): ?>
                            <td data-nivel="<?= isset($Escalas[$semana]['Segunda']['Noite'][$pos]['coroinha'])
                                ? $coroinhasMap[$Escalas[$semana]['Segunda']['Noite'][$pos]['coroinha']]->nivel
                                : '' ?>">
                                <?= isset($Escalas[$semana]['Segunda']['Noite'][$pos]['coroinha'])
                                    ? $coroinhasMap[$Escalas[$semana]['Segunda']['Noite'][$pos]['coroinha']]->nomeCoroinha
                                    : '--' ?>
                            </td>
                        <?php endfor; ?>
                        <!-- comunidade -->
                        <td colspan="3">
                            <?= $EscalasCom[$semana]['Segunda']['Noite'][3]['comunidade'] ?? '--' ?>
                        </td>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
        </table>
    </main>

    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="JS/escalaIndex.js"></script>
</body>

</html>