<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ... meta tags, título e icone ... -->
    <?php
    echo isset($description) && !empty($description) ? '<meta name="description" content="' . $description . '">' : '';
    ?>
    <title><?php echo $title; ?></title>
    <link rel="icon" href="<?php echo BASE_URL; ?>public/img/icone.ico">

    <!-- ... estilos ... -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/styles/default.css">
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
    <main>
        <div class="formCodigo">
            <div class="centralizador">
                <form id="verificationForm">
                    <a href="<?php echo BASE_URL; ?>search" class="back-button dark">
                        <i class="fas fa-arrow-left" style="margin-right: 5px;"></i> Voltar
                    </a>
                    <h4>Enviamos um código de verificação de 8 caracteres para seu e-mail. Informe-o abaixo:</h4>
                    <div class="linha-form mt-4">
                        <input type="text" id="codeInput" class="form-control input-code" placeholder="Código de verificação" required>
                        <button type="submit" class="btn-5">Verificar</button>
                    </div>
                    <div id="success-message" style="color: green; display: none; margin-top: 10px;"></div>
                    <div id="error-message" style="color: red; display: none; margin-top: 10px;"></div>
                    <button type="button" id="resendCodeButton" class="btn btn-secondary">Reenviar Código</button>
                </form>

            </div>
        </div>
    </main>

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        const email = "<?php echo isset($email) ? $email : ''; ?>";
    </script>

    <!-- js files (body) -->
    <?php
    foreach ($scripts_body as $script) {
        echo '<script src="' . BASE_URL . 'public/script/' . $script . '.js"></script>';
    }
    ?>

</body>

</html>