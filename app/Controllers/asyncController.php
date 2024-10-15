<?php

require __DIR__ . '/../../vendor/autoload.php';

use Predis\Client;

class asyncController extends Controller
{
    private $access_token;
    private $user_id;
    private $usersModel;

    public function __construct()
    {
        session_name('pesquisaviral');
        session_start();

        $this->access_token = 'EAAJog2OT3zQBO3m1yqGXnPLv4WFJCgQobb3y2Hqx4LvTWaeKHkiC4WEqMcEwZA7TwPLRuehXIHCu7GwaiCOtviXTYEFZBcPq3HJsRCdIr9zJysi4ZCBJGlujo0SYAm4gkDQOYH1UA50cBqePG2SvKhWKILrLtL7Se4zsqKlt5ABbRZA9Q0MkJbEZCvTNyD5G1qFIkoar2';
        $this->user_id = '17841461803934118';
    }

    // Método para processar as mensagens da fila 'media_collection_queue'
    public function worker()
    {
        // Conecta ao servidor Redis usando variáveis de ambiente ou localhost como padrão
        $redisHost = getenv('REDIS_HOST') ?: 'localhost';
        $redisPort = getenv('REDIS_PORT') ?: 6379;

        $redis = new Client([
            'scheme' => 'tcp',
            'host' => $redisHost,
            'port' => $redisPort,
        ]);

        // Loop infinito para processar as mensagens da fila
        while (true) {
            // Remove uma mensagem da fila 'media_collection_queue'
            $message = $redis->lpop('media_collection_queue');

            // Verifica se há mensagens na fila
            if ($message !== null) {
                // Decodifica a mensagem JSON
                $messageData = json_decode($message, true);

                // Verifica se a mensagem foi decodificada com sucesso
                if ($messageData !== null) {
                    // Obtém os dados do usuário e o nome de usuário
                    $user = $messageData['user'];
                    $username = $messageData['username'];

                    // Executa a coleta de mídias para o usuário
                    $this->collectMedia($user, $username);
                } else {
                    echo 'Erro ao decodificar a mensagem JSON.';
                }
            } else {
                // Aguarda 1 segundo antes de verificar novamente a fila
                echo 'Nenhuma mensagem na fila.';
                sleep(1);
            }
        }
    }

    private function collectMedia($user, $username)
    {
        // Cria uma instância da API do Instagram
        $api = new API($this->access_token, $this->user_id);

        // Array para armazenar todas as mídias coletadas
        $allMedia = [];

        $cacheKey = 'media_' . $user;
        $this->storeInCache($cacheKey, $allMedia);

        // Obtém o primeiro conjunto de mídias
        $mediaSet = $api->getMediaSet($username);

        // Verifica se há mídias para coletar
        if ($mediaSet) {
            // Adiciona as mídias ao array
            $allMedia = array_merge($allMedia, $mediaSet['data']);

            $cacheKey = 'media_' . $user;
            $this->storeInCache($cacheKey, $allMedia);

            if (isset($mediaSet['paging']['cursors']['after'])) {

                // Verifica se há mais páginas disponíveis para coletar mais mídias
                do {
                    // Obtém o cursor para a próxima página
                    $afterCursor = $mediaSet['paging']['cursors']['after'];

                    // Obtém o próximo conjunto de mídias usando o cursor
                    $mediaSet = $api->getMediaSet($username, $afterCursor);

                    // Verifica se o próximo conjunto de mídias foi obtido com sucesso
                    if ($mediaSet) {
                        // Adiciona as mídias ao array
                        $allMedia = array_merge($allMedia, $mediaSet['data']);
                        // Após coletar todas as mídias, você pode processá-las ou armazená-las conforme necessário
                        $cacheKey = 'media_' . $user;
                        $this->storeInCache($cacheKey, $allMedia);
                    } else {
                        break;
                    }
                } while (isset($mediaSet['paging']['cursors']['after']));
            }
        }

        // Chama um método para finalizar a pesquisa, salvando-a no banco de dados e removendo do cache
        $this->finalizaSearch($user, $username);
    }

    // Método para verificar o progresso da pesquisa
    public function progress()
    {
        // Conecta ao servidor Redis
        $redis = new Client();

        // Verifica se a busca foi marcada como concluída
        $searchFinishedKey = 'search_finished_' . $_SESSION['email'];
        $searchFinished = $redis->get($searchFinishedKey);

        if ($searchFinished) {
            $progress = 100; // Se a busca foi concluída, o progresso é 100%

            // Remove a chave de busca concluída do cache
            $redis->del($searchFinishedKey);
        } else {
            // Recupera a contagem total de mídias do perfil do cache
            $profileInfo = $redis->get('profileInfo_' . $_SESSION['email']);
            $profileInfo = json_decode($profileInfo, true);
            $totalMediaCount = $profileInfo['media_count'];

            // Recupera as mídias coletadas do cache
            $collectedMedia = $redis->get('media_' . $_SESSION['email']);
            $collectedMedia = json_decode($collectedMedia, true);
            $collectedMediaCount = count($collectedMedia);

            // Calcula o progresso da pesquisa
            $progress = ($collectedMediaCount / $totalMediaCount) * 100;
        }

        // Retorna o progresso como JSON
        header('Content-Type: application/json');
        echo json_encode(['progress' => $progress]);
        exit();
    }

    private function finalizaSearch($user, $username)
    {
        // Instancia o modelo de usuários
        $this->usersModel = new Usuarios();

        // Conecta ao servidor Redis
        $redis = new Client();

        // Recupera os dados de mídia do cache
        $mediaData = json_decode($redis->get('media_' . $user), true);

        // Recupera os dados de perfil do cache
        $profileData = json_decode($redis->get('profileInfo_' . $user), true);

        // Verifica se ambos os conjuntos de dados foram recuperados com sucesso
        if ($mediaData && $profileData) {

            // Organiza os dados de mídia conforme necessário
            $mediaDocument = ['media' => ['data' => $mediaData]];

            // Mescla os dados de perfil com os dados de mídia
            $mergedData = array_merge($profileData, $mediaDocument);

            // Salva resultados da pesquisa na coleção de usuários
            $result = $this->usersModel->saveSearchResult($user, $username, $mergedData);

            if ($result) {

                // Incrementa o número de pesquisas mensais feitas pelo usuário
                $this->usersModel->incrementUserSearchCount($user);

                // Chamada ao método para registrar pesquisa no model Searches
                $searchesModel = new Searches();
                $searchesModel->registerSearch($username, $profileData['profile_picture_url']);
            } else {
                // Falha: os resultados da pesquisa não puderam ser salvos
                echo "Error saving search.";
            }

            // Marca a busca como concluída no cache
            $this->markSearchAsFinished($user);

            // Remover os dados do cache
            $this->removeFromCache('profileInfo_' . $user);
            $this->removeFromCache('media_' . $user);
        } else {
            // Caso não seja possível recuperar algum dos conjuntos de dados
            // Trate esse cenário conforme necessário
        }
    }

    // Método para armazenar dados em cache
    private function storeInCache($cacheKey, $data)
    {
        // Conecta ao servidor Redis
        $redis = new Client();

        // Converte os dados em JSON
        $jsonData = json_encode($data);

        // Armazena os dados em cache com um tempo de expiração de 1 hora (3600 segundos)
        $redis->setex($cacheKey, 3600, $jsonData);
    }

    private function removeFromCache($cacheKey)
    {
        // Conecta ao servidor Redis
        $redis = new Client();

        // Remove os dados correspondentes ao cacheKey
        $redis->del($cacheKey);
    }

    private function markSearchAsFinished($user)
    {
        // Conecta ao servidor Redis
        $redis = new Client();

        // Marca a busca como concluída no cache
        $cacheKey = 'search_finished_' . $user;
        $redis->set($cacheKey, true);
    }
}
