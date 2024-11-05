<?php 

require 'autoload.php';

//Configura URL da aplicação
include "./app/Core/Config.php"; 

//Inclui funções (Helpers)
include "./app/Helpers/firstName.php"; 
include "./app/Helpers/formatarNumero.php"; 
include "./app/Helpers/formatarNumeroAbreviado.php";
include "./app/Helpers/sanitizeInput.php"; 

$core = new Core();