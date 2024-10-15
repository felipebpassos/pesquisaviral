<?php

require __DIR__ . '/../../vendor/autoload.php';

use MongoDB\Client;
use Dotenv\Dotenv;

class Conexao {

    private static $instancia;

    private function __construct() {}

    public static function getConexao()
    {
        // Carregar variáveis de ambiente do arquivo .env
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        // Parâmetros de conexão com o MongoDB, extraídos do .env
        $uri = $_ENV['MONGODB_URI']; // URI de conexão com o servidor MongoDB
        $dbname = $_ENV['MONGODB_DB']; // Nome do banco de dados

        // Verifica se a instância já foi criada
        if (!isset(self::$instancia)) {

            // Tenta criar a conexão com o MongoDB
            try {
                self::$instancia = new Client($uri);
            } catch (Exception $error) {
                // Em caso de erro, exibe uma mensagem
                echo 'Erro: ' . $error->getMessage();
                return null;
            }
        }

        // Verifica se a conexão foi criada com sucesso
        if (self::$instancia) {
            // Retorna a instância da conexão com o MongoDB e seleciona o banco de dados
            return self::$instancia->selectDatabase($dbname);
        } else {
            // Retorna null se a conexão não foi criada com sucesso
            return null;
        }
    }
}
