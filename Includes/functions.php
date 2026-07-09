<?php

function classeNivel(?string $nivel): string
{
    return match ($nivel) {
        'Nível 1' => 'nivel1',
        'Nível 2' => 'nivel2',
        'Acólito' => 'acolito',
        default => '',
    };
}