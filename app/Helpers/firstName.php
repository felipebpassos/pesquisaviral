<?php

function firstName($string) {
    // Divide a string em palavras
    $name = explode(' ', $string);
    
    // Retorna a primeira palavra
    return $name[0];
}
