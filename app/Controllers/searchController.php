<?php

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

class searchController extends Controller
{
    private $sessao;
    private $usersModel;
    private $searchesModel;
    private $rules;
    private $user;
    private $plan;
    private $access_token;
    private $user_id;
    private $redis;

    public function __construct()
    {
        // Carregar variáveis de ambiente do arquivo .env
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        session_name('pesquisaviral');
        session_start();

        // Instanciar as classes Sessao e Rules
        $this->sessao = new Sessao();
        $this->rules = new Rules();
        $this->usersModel = new Usuarios();
        $this->searchesModel = new Searches();

        // Verificar a sessão do usuário
        if (!isset($_SESSION['access_token']) || !$this->sessao->validarToken($_SESSION['access_token']) || !isset($_SESSION['email'])) {
            session_destroy();
            header("Location: " . BASE_URL . "login");
            exit();
        } else {
            $user_data = $this->usersModel->getUsuario($_SESSION['email']);
            $this->user = json_decode($user_data, true);
            $this->plan = $this->rules->getPlan($this->user);

            $this->access_token = $_ENV['ACCESS_TOKEN'];
            $this->user_id = $_ENV['USER_ID'];
        }
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

    public function index()
    {
        // Defina a variável de sessão para indicar a página atual
        $_SESSION['page'] = 'search';

        //set template
        $template = 'painel_temp';

        $data['user'] = $this->user;
        $data['plan'] = $this->plan;

        $user_searches = $this->usersModel->getUserSearches($_SESSION['email']);
        $data['userSearches'] = $user_searches;

        //set page data
        $data['view'] = 'search';
        $data['title'] = 'Pesquisa Viral';
        $data['description'] = '';
        $data['styles'] = array('search', 'verificador');
        $data['scripts_head'] = array('Config');
        $data['scripts_body'] = array('assync-profile-search', 'results', 'verificarConta', 'toggle-menu');

        //load view
        $this->loadTemplates($template, $data);
    }

    public function result($username = null)
    {

        if (!$username) {
            //set template
            $template = 'error';
            //load view
            $this->loadTemplates($template, []);
            exit;
        }

        $data['results'] = $this->usersModel->getSearchResult($_SESSION['email'], $username);

        if (!$data['results']) {
            //set template
            $template = 'error';
            //load view
            $this->loadTemplates($template, []);
            exit;
        }

        // Defina a variável de sessão para indicar a página atual
        $_SESSION['page'] = 'search';

        $data['user'] = $this->user;
        $data['plan'] = $this->plan;
        $data['username'] = $username;

        //set template
        $template = 'painel_temp';

        //set page data
        $data['view'] = 'result';
        $data['title'] = $username . ' | Pesquisa Viral';
        $data['description'] = '';
        $data['styles'] = array('search', 'verificador');
        $data['scripts_head'] = array('Config');
        $data['scripts_body'] = array('posts', 'mostrar-mais', 'verificarConta', 'send-search', 'toggle-menu');

        //load view
        $this->loadTemplates($template, $data);
    }

    public function startSearch()
    {
        try {

            if ($_SERVER["REQUEST_METHOD"] !== "POST") {
                throw new Exception('Método de requisição inválido.', 405);
            }

            $username = isset($_POST["account-name"]) ? sanitizeInput($_POST["account-name"]) : '';
            if (empty($username)) {
                throw new Exception('Nome de usuário não foi fornecido.', 400);
            }

            if (!$this->rules->verifySearchLimits($this->user)) {
                throw new Exception('Limite de pesquisas atingido.', 403);
            }

            $this->redis = $this->connectToRedis();
            if (!$this->redis) {
                throw new Exception("Falha ao conectar ao Redis");
            }

            $cacheKey = 'profileInfo_' . $_SESSION['email'];
            $cachedData = $this->retrieveFromCache($cacheKey);

            if ($cachedData) {
                throw new Exception('Pesquisa em andamento.', 409);
            }

            $api = new API($this->access_token, $this->user_id);
            $profileInfo = $api->getProfileInfo($username);

            if ($profileInfo === false) {
                throw new Exception('Erro ao obter as informações do perfil.', 404);
            }

            $this->storeInCache($cacheKey, $profileInfo);
            $this->setQueue($_SESSION['email'], $username);

            $this->returnProfileInfo($profileInfo);
        } catch (Exception $e) {
            http_response_code($e->getCode());
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]);
        }
    }

    public function checkSearch()
    {
        try {
            // Conectar ao Redis
            $this->redis = $this->connectToRedis();
            if (!$this->redis) {
                throw new Exception("Falha ao conectar ao Redis");
            }

            $finishedKey = 'finished_' . $_SESSION['email'];
            $profileKey = 'profileInfo_' . $_SESSION['email'];

            // Verifica se a pesquisa foi finalizada
            $finishedData = $this->retrieveFromCache($finishedKey);

            if ($finishedData) {

                $this->removeFromCache('finished_' . $_SESSION['email']);

                http_response_code(200); // OK
                echo json_encode([
                    'status' => 'finished',
                    'code' => 200,
                    'message' => 'Pesquisa concluída encontrada no cache.',
                    'data' => $finishedData
                ]);

                // Salva resultados da pesquisa na coleção de usuários
                $result = $this->usersModel->saveSearchResult(
                    $_SESSION['email'],
                    $finishedData['username'],
                    $finishedData['merged_data']
                );

                if ($result) {
                    // Incrementa o número de pesquisas mensais feitas pelo usuário
                    $this->usersModel->incrementUserSearchCount($_SESSION['email']);

                    // Registra a pesquisa no histórico de pesquisas
                    if (isset($finishedDataArray['merged_data']['profile_picture_url'])) {
                        $this->searchesModel->registerSearch(
                            $finishedData['username'],
                            $finishedData['merged_data']['profile_picture_url']
                        );
                    }
                } else {
                    error_log("Error saving search results for user: " . $_SESSION['email']);
                }

                return $finishedData;
            }

            // Verifica se a pesquisa está em andamento
            $profileData = $this->retrieveFromCache($profileKey);
            if ($profileData) {
                http_response_code(200); // OK
                echo json_encode([
                    'status' => 'in_progress',
                    'code' => 200,
                    'message' => 'Pesquisa em andamento encontrada no cache.',
                    'data' => $profileData
                ]);
                return $profileData;
            }

            // Caso não haja pesquisa em andamento nem concluída, retorna null
            http_response_code(404); // Not Found
            echo json_encode([
                'status' => 'not_found',
                'code' => 404,
                'message' => 'Nenhuma pesquisa em andamento ou concluída encontrada para o usuário.'
            ]);
            return null;
        } catch (Exception $e) {
            error_log($e->getMessage());

            // Em caso de erro, retornar uma resposta de erro
            http_response_code(500); // Internal Server Error
            echo json_encode([
                'status' => 'error',
                'code' => 500,
                'message' => 'Erro ao verificar pesquisa: ' . $e->getMessage()
            ]);
            return null;
        }
    }

    private function retrieveFromCache($cacheKey)
    {
        $cachedData = $this->redis->get($cacheKey);
        return $cachedData ? json_decode($cachedData, true) : null;
    }

    private function storeInCache($cacheKey, $data)
    {
        $jsonData = json_encode($data);
        $this->redis->setex($cacheKey, 1500, $jsonData);
    }

    public function setQueue($user, $username)
    {
        try {
            $message = json_encode(['user' => $user, 'username' => $username]);
            $this->redis->rpush('media_collection_queue', $message);
            return true;
        } catch (Exception $e) {
            echo 'Erro ao adicionar mensagem à fila: ' . $e->getMessage();
            return false;
        }
    }

    private function returnProfileInfo($profileInfo)
    {
        header('Content-Type: application/json');
        echo json_encode($profileInfo);
        exit();
    }

    private function removeFromCache($cacheKey)
    {
        // Remove os dados correspondentes ao cacheKey
        $this->redis->del($cacheKey);
    }
}
