<?php

class painelController extends Controller
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
        $_SESSION['page'] = null;

        //set template
        $template = 'painel_temp';

        $searchesModel = new Searches();

        $data['user'] = $this->user;
        $data['plan'] = $this->plan;
        $data['topSearches'] = $searchesModel->getTopSearches();

        //set page data
        $data['view'] = 'painel';
        $data['title'] = 'Pesquisa Viral';
        $data['description'] = '';
        $data['styles'] = array('search', 'search-bar');
        $data['scripts_head'] = array('');
        $data['scripts_body'] = array('progress', 'send-search');

        //load view
        $this->loadTemplates($template, $data);
    }

    public function analysis()
    {
        // Defina a variável de sessão para indicar a página atual
        $_SESSION['page'] = 'analysis';

        //set template
        $template = 'painel_temp';

        $data['user'] = $this->user;
        $data['plan'] = $this->plan;

        //set page data
        $data['view'] = 'analysis';
        $data['title'] = 'Pesquisa Viral | Analysis';
        $data['description'] = '';
        $data['styles'] = array('');
        $data['scripts_head'] = array('');
        $data['scripts_body'] = array('');

        //load view
        $this->loadTemplates($template, $data);
    }

    public function hashtags()
    {
        // Defina a variável de sessão para indicar a página atual
        $_SESSION['page'] = 'hashtags';

        //set template
        $template = 'painel_temp';

        //set page data
        $data['view'] = 'hashtags';
        $data['title'] = 'Viral Search | Hashtags';
        $data['description'] = '';
        $data['styles'] = array('');
        $data['scripts_head'] = array('');
        $data['scripts_body'] = array('');

        //load view
        $this->loadTemplates($template, $data);
    }

    public function favorites()
    {
        // Defina a variável de sessão para indicar a página atual
        $_SESSION['page'] = 'favorites';

        //set template
        $template = 'painel_temp';

        //set page data
        $data['view'] = 'favorites';
        $data['title'] = 'Viral Search | Favorites';
        $data['description'] = '';
        $data['styles'] = array('');
        $data['scripts_head'] = array('');
        $data['scripts_body'] = array('');

        //load view
        $this->loadTemplates($template, $data);
    }

    public function plans()
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

    public function about()
    {
        // Defina a variável de sessão para indicar a página atual
        $_SESSION['page'] = 'about';

        //set template
        $template = 'painel_temp';

        //set page data
        $data['view'] = 'about';
        $data['title'] = 'Sobre  | Pesquisa Viral';
        $data['description'] = '';
        $data['styles'] = array('');
        $data['scripts_head'] = array('');
        $data['scripts_body'] = array('toggle-menu');

        //load view
        $this->loadTemplates($template, $data);
    }
}
