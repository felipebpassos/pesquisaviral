<?php

require __DIR__ . '/../../vendor/autoload.php';

use Predis\Client;

class searchController extends Controller
{

    private $sessao;
    private $usersModel;
    private $rules;
    private $user;
    private $plan;
    private $access_token;
    private $user_id;

    public function __construct()
    {
        session_name('pesquisaviral');
        session_start();

        // Instanciar a classe Sessao
        $this->sessao = new Sessao();
        // Instanciar a classe Rules
        $this->rules = new Rules();
        // Instancia o modelo de usuários
        $this->usersModel = new Usuarios();

        // Verificar se a sessão existe e está correta
        if (!isset($_SESSION['access_token']) || !$this->sessao->validarToken($_SESSION['access_token']) || !isset($_SESSION['email'])) {
            // Se a sessão não estiver correta, redirecionar para a página de login
            session_destroy();
            header("Location: " . BASE_URL . "login");
            exit();
        } else {
            // Obtém os dados do usuário
            // Decodifique o JSON para uma matriz associativa
            $user_data = $this->usersModel->getUsuario($_SESSION['email']);
            $this->user = json_decode($user_data, true);

            $this->plan = $this->rules->getPlan($this->user);

            $this->access_token = 'EAAJog2OT3zQBO6qZCz1q5QR5ezZBbF1TF9Lfh8eQSzYZBHvmoC6AhIJtuswhoyR05g1E34MwbbWW8ZB381JsWwzAytc5j8sLWuHRoMfrYdxQo5ngkz1V8wqhINTXwFq8JLtq2sxdL3WK2NZC5FYh0s8LDHZBUM2zEOXlZB6S0ipHhMbPrqVG6OEZBgXB6ydAyNQJl4kBXD4x';
            $this->user_id = '17841461803934118';
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
        $data['scripts_body'] = array('assync-profile-search', 'results', 'verificarConta');

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
        $data['scripts_body'] = array('posts', 'mostrar-mais', 'verificarConta', 'send-search');

        //load view
        $this->loadTemplates($template, $data);
    }

    public function processing()
    {
        $api = new API($this->access_token, $this->user_id);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $username = isset($_POST["account-name"]) ? $_POST["account-name"] : '';

            if (!empty($username)) {

                $_SESSION['username'] = $username;
                $_SESSION['results'] = $api->getAccountData($username);

                header('Location: ' . BASE_URL . 'search/result/' . $username);
                exit(); // Certifica-se de que o script seja encerrado após o redirecionamento

            } else {
                echo 'ERRO';
            }
        }
    }

    // Método para iniciar a pesquisa
    public function startSearch()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Captura o nome de usuário do formulário
            $username = isset($_POST["account-name"]) ? trim($_POST["account-name"]) : '';

            // Verifica se o nome de usuário foi fornecido
            if (empty($username)) {
                http_response_code(400); // Bad Request
                echo json_encode(['status' => 'error', 'code' => 400, 'message' => 'Nome de usuário não foi fornecido.']);
                return;
            }

            $api = new API($this->access_token, $this->user_id);

            // Verifica se o usuário tem permissão para continuar a pesquisa com base no limite de buscas
            if (!$this->rules->verifySearchLimits($this->user)) {
                http_response_code(403); // Forbidden
                echo json_encode(['status' => 'error', 'code' => 403, 'message' => 'Limite de pesquisas atingido. Por favor, atualize seu plano.']);
                return;
            }

            // Define a chave do cache para o usuário
            $cacheKey = 'profileInfo_' . $_SESSION['email'];

            // Verifica se já existe cache para este usuário
            $cachedData = $this->retrieveFromCache($cacheKey);
            if ($cachedData) {
                http_response_code(409); // Conflict
                echo json_encode(['status' => 'error', 'code' => 409, 'message' => 'Pesquisa em andamento. Por favor, aguarde antes de iniciar uma nova pesquisa.']);
                return;
            }

            // Inicia a pesquisa e retorna as informações iniciais
            $profileInfo = $api->getProfileInfo($username);

            // Verifica se as informações do perfil foram obtidas com sucesso
            if ($profileInfo === false) {
                http_response_code(404); // Not Found
                echo json_encode(['status' => 'error', 'code' => 404, 'message' => 'Erro ao obter as informações do perfil.']);
                return;
            }

            // Armazena as informações do perfil em cache
            $this->storeInCache($cacheKey, $profileInfo);

            // Inicia o processo assíncrono de coleta de mídias
            $this->setQueue($_SESSION['email'], $username);

            // Retorna as informações do perfil ao usuário
            $this->returnProfileInfo($profileInfo);
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['status' => 'error', 'code' => 405, 'message' => 'Método de requisição inválido.']);
        }
    }

    // Método para recuperar dados do cache
    private function retrieveFromCache($cacheKey)
    {
        // Conecta ao servidor Redis usando variáveis de ambiente ou localhost como padrão
        $redisHost = getenv('REDIS_HOST') ?: 'localhost';
        $redisPort = getenv('REDIS_PORT') ?: 6379;

        $redis = new Client([
            'scheme' => 'tcp',
            'host' => $redisHost,
            'port' => $redisPort,
        ]);

        // Verifica se a chave existe no cache
        $cachedData = $redis->get($cacheKey);

        return $cachedData ? json_decode($cachedData, true) : null;
    }

    // Método para armazenar dados em cache
    private function storeInCache($cacheKey, $data)
    {
        // Conecta ao servidor Redis usando variáveis de ambiente ou localhost como padrão
        $redisHost = getenv('REDIS_HOST') ?: 'localhost';
        $redisPort = getenv('REDIS_PORT') ?: 6379;

        $redis = new Client([
            'scheme' => 'tcp',
            'host' => $redisHost,
            'port' => $redisPort,
        ]);

        // Converte os dados em JSON
        $jsonData = json_encode($data);

        // Armazena os dados em cache com um tempo de expiração de 25 min (1500 segundos)
        $redis->setex($cacheKey, 1500, $jsonData);
    }

    // Método para verificar se há pesquisa em andamento e retornar informações do perfil
    public function checkSearch()
    {

        $cacheKey = 'profileInfo_' . $_SESSION['email'];

        // Tenta recuperar dados do cache
        $cachedData = $this->retrieveFromCache($cacheKey);

        // Se houver dados no cache, retorna as informações do perfil
        if ($cachedData) {
            http_response_code(200); // OK
            echo json_encode(['status' => 'success', 'code' => 200, 'message' => 'Pesquisa em andamento encontrada no cache.', 'data' => $cachedData]);
            return $cachedData;
        }

        // Caso contrário, não há pesquisa em andamento, e o método retorna null
        return null;
    }

    // Método para retornar as informações do perfil ao usuário
    private function returnProfileInfo($profileInfo)
    {
        // Envie as informações do perfil como resposta JSON ao usuário
        header('Content-Type: application/json');
        echo json_encode($profileInfo);
        exit(); // Encerre o script após enviar a resposta
    }

    // Método para iniciar o processo assíncrono de coleta de mídias
    public function setQueue($user, $username)
    {
        try {
            // Conecta ao servidor Redis usando variáveis de ambiente ou localhost como padrão
            $redisHost = getenv('REDIS_HOST') ?: 'localhost';
            $redisPort = getenv('REDIS_PORT') ?: 6379;

            $redis = new Client([
                'scheme' => 'tcp',
                'host' => $redisHost,
                'port' => $redisPort,
            ]);

            // Envia uma mensagem para a fila 'media_collection_queue'
            $message = json_encode(['user' => $user, 'username' => $username]);
            $redis->rpush('media_collection_queue', $message);

            return true;
        } catch (Exception $e) {
            // Trata erros de conexão ou operação com o Redis
            echo 'Erro ao adicionar mensagem à fila: ' . $e->getMessage();
            return false;
        }
    }
}
