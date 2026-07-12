<?php

spl_autoload_register(function ($class) {
    require_once "../Classes/{$class}.class.php";
});

$cal = new Calendario();

$eventos = [];

foreach ($cal->all() as $e) {

    $eventos[] = [
        "id" => $e->idCalendario,
        "title" => $e->titulo,
        "start" => $e->dataInicio,
        "end" => $e->dataFim,
        "backgroundColor" => $e->corDataCalendario,
        "borderColor" => $e->corDataCalendario,
        "textColor" => "#fff",

        "extendedProps" => [
            "descricao" => $e->descricao,
            "local" => $e->local,
            "cor" => $e->corDataCalendario
        ]
    ];

}

header("Content-Type: application/json");
echo json_encode($eventos);