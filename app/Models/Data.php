<?php

require_once 'Conexao.php';

Class Data {

    private $con;

    public function __construct() {

        $this->con = Conexao::getConexao();

    }

}