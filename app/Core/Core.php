<?php

class Core
{

    public function __construct()
    {

        $this->run();

    }

    public function run()
    {

        // Controlador e o método padrão
        // Default controller and method
        $controller = 'homeController';
        $method = 'index';
        $parameters = array();

        // Verifica se a variável GET 'pag' está definida
        // Check if the 'pag' GET variable is defined
        if (isset($_GET['pag'])) {

            $url = $_GET['pag'];

        }

        // Se a URL não estiver vazia (contém informações após o domínio)
        // If the URL is not empty (contains information after the domain)
        if (!empty($url)) {

            // Quebra a URL em partes separadas por '/'
            // Split the URL into parts separated by '/'
            $url = explode('/', $url);

            $controller = $url[0] . 'Controller';
            array_shift($url);

            // Verifica se há um método específico na URL
            // Check if there is a specific method in the URL
            if (isset($url[0]) && !empty($url[0])) {

                $method = $url[0];
                array_shift($url);

            }

            // Os elementos restantes representam os parâmetros do método
            // The remaining elements represent the method parameters
            if (count($url) > 0) {
                $parameters = $url;
            }

            // Verifica se o arquivo do controlador existe e se o método especificado está disponível nele
            // Check if the controller file exists and if the specified method is available in it
            $caminho = __DIR__ . '/../Controllers/' . $controller . '.php';

            if (!file_exists($caminho) || !method_exists($controller, $method)) {
                // Caso o arquivo ou método não existam, redefine o controlador e método padrão
                // If the file or method does not exist, reset the default controller and method
                $controller = 'errorController';
                $method = 'index';
                $parameters = array();
            }

        }

        // Instancia o controlador e chama o método com os parâmetros
        // Instantiate the controller and call the method with the parameters
        $c = new $controller;
        call_user_func_array(array($c, $method), $parameters);
    }
}

?>