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
    <link rel="icon" href="./public/img/favicon.ico">

    <!-- ... estilos ... -->
    <?php if (isset($styles) && !empty($styles)) : ?>
        <link rel="stylesheet" href="./public/styles/<?php echo $styles; ?>.css">
    <?php endif; ?>

</head>

<body>
    <main>
    <p>404 - Página não encontrada! :&#40;</p>
        <a href="<?php echo BASE_URL; ?>">Voltar para a Home</a>
    </main>
</body>

</html>