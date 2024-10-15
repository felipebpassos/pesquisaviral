<?php

class plansController extends Controller
{

    private $sessao;
    private $usersModel;
    private $rules;
    private $user;
    private $plan;

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
        }
    }

    public function index()
    {
        // Defina a variável de sessão para indicar a página atual
        $_SESSION['page'] = 'plans';

        //set template
        $template = 'painel_temp';

        //set page data
        $data['view'] = 'plans';
        $data['title'] = 'Viral Search | Plans';
        $data['description'] = '';
        $data['styles'] = array('');
        $data['scripts_head'] = array('');
        $data['scripts_body'] = array('');

        //load view
        $this->loadTemplates($template, $data);
    }

    public function verify_email()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verifique se o e-mail foi enviado no POST
            if (isset($_POST['email'])) {
                $email = trim($_POST['email']);

                // Instancia o Model Rules
                $rules = new Rules();

                // Verifica se o e-mail está na lista VIP
                if ($rules->verifyEmailInList($email)) {
                    // Gera e envia o código de verificação
                    $code = $rules->sendEmailWithCode($email);

                    if ($code) {

                        $_SESSION['email-verified'] = $email;

                        // Retorna sucesso e redireciona para autenticação de dois fatores
                        echo json_encode([
                            'status' => 'success',
                            'message' => 'E-mail verificado com sucesso. O código foi enviado.',
                            'redirect_url' => BASE_URL . '/painel/two_factor'
                        ]);
                    } else {
                        // Caso falhe ao enviar o e-mail
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Falha ao enviar o código de verificação. Tente novamente.'
                        ]);
                    }
                } else {
                    // E-mail não está na lista VIP
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'O e-mail fornecido não está autorizado para o teste gratuito.'
                    ]);
                }
            } else {
                // Caso o e-mail não seja enviado
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Nenhum e-mail foi fornecido.'
                ]);
            }
        } else {
            // Responde com erro se a requisição não for POST
            echo json_encode([
                'status' => 'error',
                'message' => 'Método inválido. Apenas requisições POST são permitidas.'
            ]);
        }
    }

    public function auth2FA()
    {
        if (!$_SESSION['email-verified']) {
            // Redireciona para a página inicial caso não tenha email verificado salvo em sessão
            header('Location: ' . BASE_URL . 'search');
            exit;
        }

        //set template
        $template = 'twoFactorAuth';

        //set page data
        $data['view'] = '';
        $data['title'] = 'Verificar email | Pesquisa Viral';
        $data['description'] = '';
        $data['styles'] = array('verificador');
        $data['scripts_head'] = array('Config');
        $data['scripts_body'] = array('verificarCode');

        $data['email'] = $_SESSION['email-verified'];

        //load view
        $this->loadTemplates($template, $data);
    }

    public function verify_code()
    {
        // Verifique se o email foi verificado e se está salvo na sessão
        if (!isset($_SESSION['email-verified']) || !isset($_SESSION['email'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Sessão inválida ou e-mail não verificado.'
            ]);
            exit;
        }

        // Verifique se a requisição é do tipo POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verifique se o código de verificação foi enviado no POST
            if (isset($_POST['code'])) {
                $verificationCode = trim($_POST['code']);
                $emailVerified = $_SESSION['email-verified']; // E-mail verificado
                $emailUsuario = $_SESSION['email']; // E-mail do usuário logado (Facebook)

                // Instancia o Model Rules e Usuarios
                $rulesModel = new Rules();
                $usuariosModel = new Usuarios();

                // Verifica o código de verificação
                if ($rulesModel->verifyVerificationCode($emailVerified, $verificationCode)) {
                    // Se o código for válido, atualiza o plano do usuário para "enterprise" por 12 meses
                    $dataExpiracao = date('Y-m-d H:i:s', strtotime('+12 months'));

                    // Atualiza o plano do usuário associado ao e-mail do Facebook ($_SESSION['email'])
                    if ($usuariosModel->updatePlan($emailUsuario, 'enterprise', $dataExpiracao)) {
                        // Código verificado com sucesso e plano atualizado
                        echo json_encode([
                            'status' => 'success',
                            'message' => 'Código verificado com sucesso. Plano atualizado para "enterprise".'
                        ]);
                    } else {
                        // Falha ao atualizar o plano
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Falha ao atualizar o plano do usuário.'
                        ]);
                    }
                } else {
                    // Código de verificação inválido
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Código de verificação inválido. Tente novamente.'
                    ]);
                }
            } else {
                // Caso o código de verificação não seja enviado
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Nenhum código de verificação foi fornecido.'
                ]);
            }
        } else {
            // Se a requisição não for POST, retorna erro
            echo json_encode([
                'status' => 'error',
                'message' => 'Método inválido. Apenas requisições POST são permitidas.'
            ]);
        }
    }
}
