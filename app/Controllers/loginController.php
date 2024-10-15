<?php

Class loginController extends Controller {

    
    // Generates the log-in page
    public function index() {

        session_name('pesquisaviral');
        session_start();

        $sessao = new Sessao();

        $sessao->verificaLogin();

        //set template
        $template = 'login';

        //set page data
        $data['view'] = '';
        $data['title'] = 'Login | Pesquisa Viral';
        $data['description'] = 'Descrição do curso';
        $data['styles'] = array('login');
        $data['scripts_head'] = array('');
        $data['scripts_body'] = array('');

        //load view
        $this->loadTemplates($template, $data);

    }

}