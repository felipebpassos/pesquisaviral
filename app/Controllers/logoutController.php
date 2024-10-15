<?php

class logoutController extends Controller
{

    private $sessao;

    public function __construct()
    {
        session_name('pesquisaviral');
        session_start();

        // Instanciar a classe Sessao
        $this->sessao = new Sessao();

        // Verificar se a sessão existe e está correta
        if (!isset($_SESSION['access_token']) || !$this->sessao->validarToken($_SESSION['access_token'])) {
            // Se a sessão não estiver correta, redirecionar para a página de login
            header("Location: " . BASE_URL . "login");
            exit();
        }

    }

    public function index()
    {
        // Limpa os dados da sessão
        session_destroy();

        // Redireciona para a página de login ou outra página de sua escolha
        header('Location: ' . BASE_URL . 'login') ;
        exit;
    }

}

