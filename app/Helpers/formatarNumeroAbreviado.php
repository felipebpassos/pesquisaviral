<?php

function formatarNumeroAbreviado($numero)
{
    $abreviacoes = array("K", "M", "B", "T"); // Pode adicionar mais conforme necessário

    $abreviacao = "";
    $formatado = $numero;

    // Loop para encontrar a abreviação correta
    foreach ($abreviacoes as $abrev) {
        if ($numero < 1000) {
            break;
        }
        $numero /= 1000;
        $abreviacao = $abrev;
    }


    // Formata o número com duas casas decimais
    $formatado = number_format($numero, 2, '.', ',');

    // Adiciona a abreviação
    $formatado .= " " . $abreviacao;

    return $formatado;
}
