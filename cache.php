<?php

require __DIR__ . '/vendor/autoload.php';

use Predis\Client;
use Dotenv\Dotenv;

// Carregar variáveis de ambiente do arquivo .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$redisUrl = $_ENV['REDIS_URL'];

try {
    // Conectar ao Redis
    $redis = new Client($redisUrl);
    $redis->connect();

    // Verificar se a conexão está funcionando
    if ($redis->isConnected()) {
        echo "Conexão bem-sucedida ao Redis.\n";

        // Obtém todas as chaves do cache
        $keys = $redis->keys('*');

        if (!empty($keys)) {
            echo "Chaves armazenadas no Redis:\n";
            foreach ($keys as $key) {
                echo "- $key\n";
            }
        } else {
            echo "Nenhuma chave encontrada no cache Redis.\n";
        }

    } else {
        echo "Falha na conexão ao Redis.\n";
    }
} catch (Exception $e) {
    // Em caso de erro, exibir a mensagem de erro
    echo "Erro ao conectar ao Redis: " . $e->getMessage() . "\n";
}
