<?php

// Conexão com o MongoDB
require 'vendor/autoload.php'; // Certifique-se de que o Composer foi executado para instalar as dependências do MongoDB

$client = new MongoDB\Client("mongodb://localhost:27017"); // Substitua pela sua URL do MongoDB
$collection = $client->viralsearch->users; // Substitua pelo seu banco de dados e coleção

// Identificação do usuário
$email = "felipebpassos@hotmail.com";

// Atualização do plano
$result = $collection->updateOne(
    [
        'email' => $email
    ],
    [
        '$set' => [
            'plan.type' => 'individual',
            'plan.start_date' => new MongoDB\BSON\UTCDateTime(), // Data de início atual
            'plan.expiration_date' => new MongoDB\BSON\UTCDateTime(strtotime('+12 month') * 1000) // 1 mês a partir da data atual
        ]
    ]
);

// Verificação do resultado da atualização
if ($result->getModifiedCount() > 0) {
    echo "Plano alterado para freemium com sucesso.";
} else {
    echo "Nenhuma alteração foi feita ou usuário não encontrado.";
}
