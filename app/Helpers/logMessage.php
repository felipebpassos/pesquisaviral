<?php

$logFile = 'C:\\xampp\\htdocs\\pesquisaviral\\debug.log';

// Verifica se o arquivo existe
if (!file_exists($logFile)) {
    // Cria o arquivo se não existir
    file_put_contents($logFile, '');
}

// Ajusta as permissões (não é sempre suportado)
chmod($logFile, 0666); // Permissões para leitura e escrita para todos

function logMessage($message) {
    $logFile = 'C:\\xampp\\htdocs\\pesquisaviral\\debug.log';
    $timestamp = date("Y-m-d H:i:s");

    // Se a mensagem for um array ou objeto, converta-a para uma string legível
    if (is_array($message) || is_object($message)) {
        $message = print_r($message, true); // Converte o array/objeto para uma string legível
    }

    file_put_contents($logFile, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
}

