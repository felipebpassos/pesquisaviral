<?php

class authController extends Controller
{

    // Configurações do OAuth2
    private $clientId = '677863474061108';
    private $clientSecret = 'ef6bb2d1761f97f2228fae9e59beca24';
    private $redirectUri = BASE_URL . 'auth/callback';
    private $authorizationUrl = 'https://www.facebook.com/v18.0/dialog/oauth';
    private $tokenUrl = 'https://graph.facebook.com/v18.0/oauth/access_token';

    // Página de login
    public function index()
    {
        // Construir URL de autorização
        $authorizationParams = array(
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'scope' => 'email', // Permissões necessárias
            'response_type' => 'code'
        );
        $authorizationUrl = $this->authorizationUrl . '?' . http_build_query($authorizationParams);

        // Redirecionar para a página de autorização do Facebook
        header("Location: $authorizationUrl");
        exit();
    }

    // Callback após o login
    public function callback()
    {
        // Verificar se o código de autorização está presente
        if (isset($_GET['code'])) {
            $code = $_GET['code'];

            try {
                // Trocar o código de autorização pelo token de acesso
                $tokenParams = array(
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'redirect_uri' => $this->redirectUri,
                    'code' => $code
                );

                // Usar cURL em vez de file_get_contents para uma melhor manipulação de erros
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $this->tokenUrl . '?' . http_build_query($tokenParams));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $tokenResponse = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                // Verificar se a requisição foi bem-sucedida
                if ($httpCode !== 200) {
                    throw new Exception("Erro ao obter token de acesso.");
                }

                $tokenData = json_decode($tokenResponse, true);

                // Verificar se o token de acesso foi recebido
                if (isset($tokenData['access_token'])) {
                    $accessToken = $tokenData['access_token'];

                    // Usar cURL para obter os dados do usuário com o token
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/me?fields=name,email&access_token=$accessToken");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $userDataResponse = curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);

                    // Verificar se a requisição foi bem-sucedida
                    if ($httpCode !== 200) {
                        throw new Exception("Erro ao obter dados do usuário.");
                    }

                    $userData = json_decode($userDataResponse, true);

                    // Verificar se os dados do usuário foram recebidos
                    if (isset($userData['name']) && isset($userData['email'])) {

                        session_name('pesquisaviral');
                        session_start();

                        // Armazenar o access token na sessão
                        $_SESSION['access_token'] = $accessToken;
                        $_SESSION['email'] = $userData['email'];

                        $usuarios = new Usuarios();
                        $email = $userData['email'];

                        // Verificar se o usuário já existe no banco de dados
                        if (!$usuarios->usuarioExiste($email)) {
                            // Se o usuário não existir, adicioná-lo ao banco de dados
                            $usuarios->setUsuario($userData['name'], $email);
                        }

                        // Redirecionar para o painel do usuário
                        header("Location: " . BASE_URL . "search");
                        exit();
                    } else {
                        throw new Exception("Erro ao obter dados do usuário.");
                    }
                } else {
                    throw new Exception("Erro ao obter token de acesso.");
                }
            } catch (Exception $e) {
                // Exibir mensagem genérica, sem expor detalhes
                echo "Ocorreu um erro. Por favor, tente novamente.";
                // Exibir detalhes do erro
                echo $e->getMessage();
                // Opcional: logar detalhes do erro em um arquivo seguro
                error_log($e->getMessage()); // Opcional: registrar o erro para depuração
            }
        } else {
            // Código de autorização ausente
            echo "Código de autorização ausente.";
        }
    }
}
