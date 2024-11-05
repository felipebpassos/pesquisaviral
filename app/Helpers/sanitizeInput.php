<?php

function sanitizeInput($data) {
    // Remove espaços em branco do início e do fim
    $data = trim($data);
    
    // Remove barras invertidas se estiverem presentes
    $data = stripslashes($data);
    
    // Converte caracteres especiais em entidades HTML
    $data = htmlspecialchars($data);
    
    // Opcional: você pode adicionar mais sanitizações, como a remoção de caracteres não permitidos
    // $data = preg_replace('/[^A-Za-z0-9\-_.]/', '', $data); // exemplo para permitir apenas letras, números e alguns caracteres especiais
    
    return $data;
}
