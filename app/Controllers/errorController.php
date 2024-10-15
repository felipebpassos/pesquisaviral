<?php

Class errorController extends Controller {

    public function index() {

        $template = 'error';

        $data['view'] = '';
        $data['title'] = 'Página não encontrada (404 error)';
        $data['description'] = 'Desculpe, a página que você está procurando não foi encontrada. Por favor, verifique a URL ou navegue para outra parte do site.';
        $data['styles'] = 'error';

        $this->loadTemplates($template, $data);

    }

}

?>