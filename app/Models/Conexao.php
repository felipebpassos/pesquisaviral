<?php

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

class Conexao
{
    private static $instancia;
    private static $database;

    private function __construct() {}

    public static function getConexao()
    {
        // Carregar variáveis de ambiente do arquivo .env
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        // Parâmetros de conexão com o MongoDB, extraídos do .env
        $uri = $_ENV['MONGODB_URI'];
        $dbname = $_ENV['MONGODB_DB'];

        // Verifica se a instância já foi criada
        if (!isset(self::$instancia)) {
            try {
                self::$instancia = new MongoDB\Client($uri);
                self::$database = self::$instancia->selectDatabase($dbname);
            } catch (Exception $error) {
                echo 'Erro: Ocorreu um problema ao conectar ao MongoDB.';
                error_log($error->getMessage());
                return null;
            }
        }

        return self::$database;
    }
}