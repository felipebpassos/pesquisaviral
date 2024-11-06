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

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- ... estilos ... -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/styles/default.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/styles/footer.css">
    <?php
    foreach ($styles as $style) {
        echo '<link rel="stylesheet" href="' . BASE_URL . 'public/styles/' . $style . '.css">' . PHP_EOL;
    }
    ?>

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

    <main>
        <div class="background">
            <img src="<?php echo BASE_URL; ?>public/img/background.jpg" alt="">
        </div>
        <div class="card">
            <!-- Logo -->
            <div class="logo"><img src="<?php echo BASE_URL; ?>public/img/logo-nova.png" alt="Logo"></div>
            <p>Clique abaixo para entrar</p>
            <a href="<?php echo BASE_URL; ?>auth/"><button>Entrar com Facebook</button></a>
            <span>Ao continuar, você estará confirmando que leu nossos<br>Termos & Condições e Políticas de Cookies</span>
        </div>
    </main>

    <!-- Rodapé -->
    <footer>
        <div class="copyright">
            &copy; 2024, Pesquisa Viral | Developed by <a href="https://simplifyweb.com.br" target="_blank">Felipe Passos</a>
        </div>
    </footer>

    <a href="https://api.whatsapp.com/send?phone=5579996010545" class="whatsapp-button" target="_blank">
        <img src="<?php echo BASE_URL; ?>public/img/msg.png" alt="Ícone do WhatsApp">
    </a>

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- js files (body) -->
    <?php
    foreach ($scripts_body as $script) {
        echo '<script src="' . BASE_URL . 'public/script/' . $script . '.js"></script>';
    }
    ?>
</body>

</html>