<?php

// Função para retornar a classe CSS de acordo com o nível do coroinha - usada em index.php
function classeNivel(?string $nivel): string
{
    return match ($nivel) {
        'Nível 1' => 'nivel1',
        'Nível 2' => 'nivel2',
        'Acólito' => 'acolito',
        default => '',
    };
}

// Função para formatar a data do banco de dados para o formato brasileiro - listaCelebracoes.php
function formatarData($dataBD, $comHora = false)
{
    if (!$dataBD) return '--';

    $formato = $comHora ? 'd/m/Y H:i' : 'd/m/Y';

    $data = date_create($dataBD);
    if (!$data) return '--';

    return date_format($data, $formato);
}

/**
 * Renderiza as <option> de coroinha, já marcando o selecionado.
 */
function renderOptionsCoroinha(array $coroinhas, ?int $selecionado, string $placeholder = '--'): void
{
    echo '<option value="" class="align-middle text-center">' . $placeholder . '</option>';

    foreach ($coroinhas as $c) {
        $sel = ($selecionado == $c->idCoroinha) ? 'selected' : '';
        echo "<option value=\"{$c->idCoroinha}\" data-nivel=\"{$c->nivel}\" {$sel} class=\"align-middle text-center\">
                {$c->nomeCoroinha}
              </option>";
    }
}

/**
 * Renderiza as <option> de Comunidade, já marcando o selecionado.
 */
function renderOptionsComunidade(array $comunidades, ?int $selecionado): void
{
    echo '<option value="">Selecionar comunidade</option>';
    foreach ($comunidades as $com) {
        $sel = ($selecionado == $com->idComunidade) ? 'selected' : '';
        echo "<option value=\"{$com->idComunidade}\" {$sel} class=\"align-middle text-center\">{$com->nomeComunidade}</option>";
    }
}

/**
 * Busca o coroinha já escalado numa posição, evitando o ?? repetido.
 */
function coroinhaSelecionado(array $Escalas, $semana, string $dia, string $turno, int $pos): ?int
{
    return $Escalas[$semana][$dia][$turno][$pos] ?? null;
}