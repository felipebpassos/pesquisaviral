<?php

require 'autoload.php';

// Crie uma instância do controller
$searchController = new asyncController();

// Chame o método worker
$searchController->worker();
