<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Definições default -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ... meta tags, título e icone ... -->
    <?php echo isset($description) && !empty($description) ? '<meta name="description" content="' . $description . '">' : ''; ?>
    <title>
        <?php echo $title; ?>
    </title>
    <link rel="icon" href="<?php echo BASE_URL; ?>public/img/favicon.ico">

    <!-- ... estilos ... -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/styles/default.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/styles/aside.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/styles/painel.css">
    <?php
    foreach ($styles as $style) {
        echo '<link rel="stylesheet" href="' . BASE_URL . 'public/styles/' . $style . '.css">' . PHP_EOL;
    }
    ?>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Scripts (head) -->
    <?php
    foreach ($scripts_head as $script) {
        echo '<script src="' . BASE_URL . 'public/script/' . $script . '.js"></script>';
    }
    ?>
</head>

<body>

    <header class="header">
        <button class="menu-toggle" id="menu-toggle">
            <i class="fas fa-bars"></i>
        </button>
    </header>

    <!-- Overlay para cobrir a tela -->
    <div class="overlay" id="overlay"></div>

    <aside>
        <a class="logo" href="<?php echo BASE_URL; ?>search/">
            <img src="<?php echo BASE_URL; ?>public/img/logo-nova.png" alt="Logo">
        </a>
        <nav>
            <ul>
                <li><a <?php echo ($_SESSION['page'] == 'search') ? 'class="atual"' : ''; ?> href="<?php echo BASE_URL; ?>search/">
                        <i class="fa-solid fa-users"></i> Pesquisas
                    </a></li>
                <li style="display: none;"><a <?php echo ($_SESSION['page'] == 'about') ? 'class="atual"' : ''; ?> href="<?php echo BASE_URL; ?>painel/about">
                        <i class="fa-solid fa-circle-question"></i> Sobre
                    </a></li>
                <li><a href="<?php echo BASE_URL; ?>logout/">
                        <i class="fa-solid fa-right-from-bracket"></i> Sair
                    </a></li>
            </ul>
        </nav>
    </aside>

    <?php

    $this->loadViewOnTemplate($view, $pageData, $model_data);

    ?>

    <a href="https://api.whatsapp.com/send?phone=5579996010545" class="whatsapp-button" target="_blank">
        <img src="<?php echo BASE_URL; ?>public/img/msg.png" alt="Ícone do WhatsApp">
    </a>

    <!-- JQuery -->
    <script
        src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
        crossorigin="anonymous"></script>
    <!-- js files (body) -->
    <?php
    foreach ($scripts_body as $script) {
        echo '<script src="' . BASE_URL . 'public/script/' . $script . '.js"></script>';
    }
    ?>


</body>

</html>