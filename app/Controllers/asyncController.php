<?php

require __DIR__ . '/../../vendor/autoload.php';

require __DIR__ . '/../../autoload.php';

use Dotenv\Dotenv;

class asyncController extends Controller
{
    private $access_token;
    private $user_id;
    private $redis;

    public function __construct()
    {
        session_name('pesquisaviral');
        session_start();

        echo 'inicio';

        // Carregar variáveis de ambiente do arquivo .env
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $this->access_token = $_ENV['ACCESS_TOKEN'];
        $this->user_id = $_ENV['USER_ID'];

        $this->redis = $this->connectToRedis();
    }

    private function connectToRedis()
    {
        // Obter a URL do Redis a partir das variáveis de ambiente
        $redisUrl = $_ENV['REDIS_URL'];

        // Verifica se a URL do Redis está definida
        if (empty($redisUrl)) {
            throw new Exception('A URL do Redis não está definida nas variáveis de ambiente.');
        }

        // Criar o cliente Redis e conectar
        try {
            $redis = new Predis\Client($redisUrl); // Certifique-se de que está usando Predis ou outra biblioteca
            $redis->connect(); // Conectar-se ao Redis
            return $redis;
        } catch (Exception $e) {
            // Em caso de erro, lançar uma exceção com uma mensagem detalhada
            throw new Exception('Erro ao conectar ao Redis: ' . $e->getMessage());
        }
    }

    // Método para processar as mensagens da fila 'media_collection_queue'
    public function worker()
    {
        // Processa todas as mensagens da fila 'media_collection_queue' até esvaziá-la
        while ($message = $this->redis->lpop('media_collection_queue')) {
            // Decodifica a mensagem JSON
            $messageData = json_decode($message, true);

            if ($messageData !== null) {
                $user = $messageData['user'];
                $username = $messageData['username'];
                $this->collectMedia($user, $username);
            } else {
                echo 'Erro ao decodificar a mensagem JSON.';
            }
        }

        echo "Todas as mensagens da fila foram processadas.\n";
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
        // Verifica se a busca foi marcada como concluída
        $searchFinishedKey = 'search_finished_' . $_SESSION['email'];
        $searchFinished = $this->redis->get($searchFinishedKey);

        if ($searchFinished) {
            $progress = 100; // Se a busca foi concluída, o progresso é 100%

            // Remove a chave de busca concluída do cache
            $this->redis->del($searchFinishedKey);
        } else {
            // Recupera a contagem total de mídias do perfil do cache
            $profileInfo = $this->redis->get('profileInfo_' . $_SESSION['email']);
            $profileInfo = json_decode($profileInfo, true);
            $totalMediaCount = $profileInfo['media_count'];

            // Recupera as mídias coletadas do cache
            $collectedMedia = $this->redis->get('media_' . $_SESSION['email']);
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
        // Recupera os dados de mídia do cache
        $mediaData = json_decode($this->redis->get('media_' . $user), true);

        // Recupera os dados de perfil do cache
        $profileData = json_decode($this->redis->get('profileInfo_' . $user), true);

        // Verifica se ambos os conjuntos de dados foram recuperados com sucesso
        if ($mediaData && $profileData) {

            // Organiza os dados de mídia conforme necessário
            $mediaDocument = ['media' => ['data' => $mediaData]];

            // Mescla os dados de perfil com os dados de mídia
            $mergedData = array_merge($profileData, $mediaDocument);

            // Cache com a marca de finalização
            $finalCacheKey = 'finished_' . $user;
            $finalCacheData = [
                'username' => $username,
                'merged_data' => $mergedData,
                'finished_at' => date('Y-m-d H:i:s') // Adiciona a data e hora de finalização
            ];

            // Remover os dados do cache
            $this->removeFromCache('profileInfo_' . $user);
            $this->removeFromCache('media_' . $user);
            // Salva em cache sem expiração, para ser salvo no banco posteriormente
            $this->storeInCache($finalCacheKey, $finalCacheData);

            echo 'teste';
        } else {
            // Caso não seja possível recuperar algum dos conjuntos de dados
            // Trate esse cenário conforme necessário
        }
    }

    // Método para armazenar dados em cache
    private function storeInCache($cacheKey, $data)
    {
        // Converte os dados em JSON
        $jsonData = json_encode($data);

        // Armazena os dados em cache com um tempo de expiração de 1 hora (3600 segundos)
        $this->redis->setex($cacheKey, 3600, $jsonData);
    }

    private function removeFromCache($cacheKey)
    {
        // Remove os dados correspondentes ao cacheKey
        $this->redis->del($cacheKey);
    }
}
