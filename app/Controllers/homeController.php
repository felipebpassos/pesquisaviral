<?php

Class homeController extends Controller {

    public function index() {

        session_name('pesquisaviral');
        session_start();

        $sessao = new Sessao();

        $sessao->verificaLogin();

        //set template
        $template = 'home';

        //set page data
        $data['view'] = '';
        $data['title'] = 'Pesquisa Viral';
        $data['description'] = 'Descrição do curso';
        $data['styles'] = array('footer', 'styles');
        $data['scripts_head'] = array('accordion-pre-set');
        $data['scripts_body'] = array('header-effect', 'accordion');

        //load view
        $this->loadTemplates($template, $data);

    }

}

?>