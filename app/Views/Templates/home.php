<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <!-- Definições default -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ... meta tags, título e icone ... -->
    <?php echo isset($description) && !empty($description) ? '<meta name="description" content="' . $description . '">' : ''; ?>
    <title>
        <?php echo $title; ?>
    </title>
    <link rel="icon" href="<?php echo BASE_URL; ?>public/img/icone.ico">

    <!-- ... estilos ... -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/styles/default.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/styles/footer.css">
    <?php
    foreach ($styles as $style) {
        echo '<link rel="stylesheet" href="' . BASE_URL . 'public/styles/' . $style . '.css">' . PHP_EOL;
    }
    ?>

    <!-- Bootstrap CSS -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,400i,500,600,600i,800,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;700;800&display=swap" rel="stylesheet">

    <!-- Scripts (head) -->
    <?php
    foreach ($scripts_head as $script) {
        echo '<script src="' . BASE_URL . 'public/script/' . $script . '.js"></script>';
    }
    ?>
</head>

<body>

    <!-- Cabeçalho -->
    <header>

        <!-- Logo -->
        <a href="#" class="logo"><img src="<?php echo BASE_URL; ?>public/img/logo-nova-2.png" alt="Logo"></a>

        <nav>
            <ul>
                <li><a href="#features">Funcionalidades</a></li>
                <li><a href="#demo">Demo</a></li>
                <li><a href="#plans">Planos</a></li>
                <li><a href="#faq">FAQ</a></li>
            </ul>
        </nav>

        <div style="display:flex;">
            <div class="translator">
                <div class="lenguage-select">
                    Português
                    <svg width="15" height="10" viewBox="0 0 42 25">
                        <path d="M3 3L21 21L39 3" stroke="#ccccc" stroke-width="7" stroke-linecap="round">
                        </path>
                    </svg>
                </div>
                <div class="op-language">
                    <div class="box" style="margin-top: 5px;">
                        <button id="en" data-id="en">English</button>
                        <button id="pt" data-id="pt">Português</button>
                    </div>
                </div>
            </div>
            <a class="login" href="<?php echo BASE_URL; ?>login/"><button class="btn-4"><i class="fa-solid fa-user"></i> Login</button></a>
        </div>

    </header>


    <main>

        <!-- Hero | Main Banner -->
        <section id="hero">
            <div class="row">
                <div class="col-md-7">

                    <div class="description">

                        <h1>Encontre conteúdo viral em segundos</h1>
                        <h5>Pesquisa Viral é a forma mais fácil de descobrir postagens
                            de melhor performance no instagram e obter análises de qualquer perfil business.
                            Economize tempo descobrindo novas ideias.<br><br>
                            Seu atalho para a criatividade nas redes sociais.
                        </h5>

                        <div class="options">
                            <a href="<?php echo BASE_URL; ?>login/"><button class="btn-5">Teste Grátis</button></a>
                            <a href="#plans">
                                <button id="premium-btn" class="btn-4">
                                    <svg x="0px" y="0px" viewBox="0 0 122.88 107.76" style="enable-background:new 0 0 122.88 107.76">
                                        <g>
                                            <path d="M21.13,83.86h80.25l12.54-34.73c0.65,0.21,1.35,0.32,2.07,0.32c3.8,0,6.89-3.08,6.89-6.89 c0-3.8-3.08-6.89-6.89-6.89c-3.8,0-6.89,3.08-6.89,6.89c0,1.5,0.48,2.88,1.29,4.01l-7.12,5.86c-9.97,8.2-16.22,4.4-14.27-8.34 l1.1-7.17c0.38,0.07,0.78,0.1,1.18,0.1c3.8,0,6.89-3.08,6.89-6.89c0-3.8-3.08-6.89-6.89-6.89c-3.8,0-6.89,3.08-6.89,6.89 c0,2.17,1.01,4.11,2.58,5.37l-1.71,2.7c-8.38,12.58-14.56,7.76-17.03-4.67l-4.41-20.31c2.47-1.05,4.21-3.49,4.21-6.35 c0-3.8-3.08-6.89-6.89-6.89c-3.8,0-6.89,3.08-6.89,6.89c0,3.18,2.15,5.85,5.07,6.65L56.46,25.1c-2.48,10.61-5.45,31.75-18.88,13.73 l-2.19-2.98c1.73-1.25,2.86-3.29,2.86-5.59c0-3.8-3.08-6.89-6.89-6.89c-3.8,0-6.89,3.08-6.89,6.89c0,3.8,3.08,6.89,6.89,6.89 c0.53,0,1.05-0.06,1.55-0.18l0.46,4.68c0.9,6.39,2.05,15.04-5.29,14.63c-3.64-0.2-5.01-1.44-7.79-3.42l-7.94-5.63 c0.89-1.16,1.42-2.61,1.42-4.19c0-3.8-3.08-6.89-6.89-6.89c-3.8,0-6.89,3.08-6.89,6.89s3.08,6.89,6.89,6.89 c0.9,0,1.75-0.17,2.54-0.48L21.13,83.86L21.13,83.86z M21.07,93.47h80.51v14.29H21.07V93.47L21.07,93.47z" />
                                        </g>
                                    </svg>
                                    Premium
                                </button>
                            </a>
                        </div>

                    </div>

                </div>
                <div class="col-md-5" style="display:flex; justify-content:center;">
                    <div class="main-img">
                        <img src="<?php echo BASE_URL; ?>public/img/isometric.jpg" alt="Isometric Ilustration" width="400">
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features">
            <div class="container mt-5">

                <div class="row">
                    <div class="col-md-4" style="display:flex; justify-content:center;">
                        <div class="card">
                            <div>
                                <img src="./public/img/feature1.jpg" alt="Feature 1">
                                <h3>Feature 1</h3>
                                <p>Description of feature 1.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" style="display:flex; justify-content:center;">
                        <div class="card">
                            <div>
                                <img src="./public/img/feature2.jpg" alt="Feature 2">
                                <h3>Feature 2</h3>
                                <p>Description of feature 2.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mt-4" style="display:flex; justify-content:center;">
                        <div class="card">
                            <div>
                                <img src="./public/img/feature3.jpg" alt="Feature 3">
                                <h3>Feature 3</h3>
                                <p>Description of feature 3.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- Plans Section -->
        <section id="plans" class="container mt-5">
            <div class="box-flex-full">
                <div class="sessao-titulo">Preços</div>
            </div>
            <h2 class="sessao-subtitulo">Escolha seu <span style="color: #FF1493; font-weight: bold;">plano</span></h2>
            <div class="row">
                <!-- Free Plan -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Gratuito</h3>
                            <p class="card-text price">R$ 0</p>
                            <ul>
                                <li>3 pesquisas de perfil</li>
                                <li>3 pesquisas de hashtag</li>
                                <li>10 downloads</li>
                                <li>Análise de perfis</li>
                            </ul>
                            <a href="<?php echo BASE_URL; ?>login/"><button class="btn-5">Comece agora</button></a>
                        </div>
                    </div>
                </div>

                <!-- Plus Plan -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Plus</h3>
                            <p class="card-text price">R$ 22</p>
                            <ul>
                                <li>20 pesquisas de perfil</li>
                                <li>20 pesquisas de hashtag</li>
                                <li>150 downloads</li>
                                <li>Análise de perfis</li>
                            </ul>
                            <a href="<?php echo BASE_URL; ?>login/"><button class="btn-5">Comece agora</button></a>
                        </div>
                    </div>
                </div>

                <!-- Enterprise Plan -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Enterprise</h3>
                            <p class="card-text price">R$ 32</p>
                            <ul>
                                <li>Pesquisas de perfil ilimitadas</li>
                                <li>Unlimited hashtag searches</li>
                                <li>Unlimited downloads</li>
                                <li>Análise de perfis</li>
                            </ul>
                            <a href="<?php echo BASE_URL; ?>login/"><button class="btn-5">Get Started</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section id="faq">
            <div class="container fade-in-element">
                <div class="box-flex-full">
                    <div class="sessao-titulo">Perguntas frequentes</div>
                </div>
                <h2 class="sessao-subtitulo">Ficou alguma <span style="color: #FF1493; font-weight: bold;">dúvida</span>?</h2>
                <ul id="lista-perguntas" class="accordion-1">
                    <div class="pergunta">
                        <li>
                            <div class="pergunta-header">
                                <p>What are the payment options?</p>
                                <svg width="15" height="10" viewBox="0 0 42 25">
                                    <path d="M3 3L21 21L39 3" stroke="rgb(100, 100, 100)" stroke-width="7" stroke-linecap="round">
                                    </path>
                                </svg>
                            </div>
                            <div class="resposta">
                                <p>We accept all major credit cards.</p><br>
                            </div>
                        </li>

                    </div>
                    <div class="pergunta">
                        <li>
                            <div class="pergunta-header">
                                <p>What is our cancelation policy?</p>
                                <svg width="15" height="10" viewBox="0 0 42 25">
                                    <path d="M3 3L21 21L39 3" stroke="rgb(100, 100, 100)" stroke-width="7" stroke-linecap="round">
                                    </path>
                                </svg>
                            </div>
                            <div class="resposta">
                                <p>Our goal is to make you happy. You can cancel at any time and won't be billed for subsequent months. No hard feelings.</p>
                                <br>
                            </div>
                        </li>
                    </div>
                    <div class="pergunta">
                        <li>
                            <div class="pergunta-header">
                                <p>In which currency are the prices?</p>
                                <svg width="15" height="10" viewBox="0 0 42 25">
                                    <path d="M3 3L21 21L39 3" stroke="rgb(100, 100, 100)" stroke-width="7" stroke-linecap="round">
                                    </path>
                                </svg>
                            </div>
                            <div class="resposta">
                                <p>Our prices are in US dollars (USD).</p>
                                <br>
                            </div>
                        </li>
                    </div>
                    <div class="pergunta">
                        <li>
                            <div class="pergunta-header">
                                <p>Can I use ViralSearch on my phone?</p>
                                <svg width="15" height="10" viewBox="0 0 42 25">
                                    <path d="M3 3L21 21L39 3" stroke="rgb(100, 100, 100)" stroke-width="7" stroke-linecap="round">
                                    </path>
                                </svg>
                            </div>
                            <div class="resposta">
                                <p>Of course! Our application is compatible with all devices that have a web browser.
                                </p>
                                <br>
                            </div>
                        </li>
                    </div>
                </ul>
            </div>
        </section>
    </main>

    <a href="https://api.whatsapp.com/send?phone=5579996010545" class="whatsapp-button" target="_blank">
        <img src="<?php echo BASE_URL; ?>public/img/msg.png" alt="Ícone do WhatsApp">
    </a>

    <!-- Rodapé -->
    <footer>
        <div class="copyright">
            &copy; 2024, Pesquisa Viral | Developed by <a href="https://www.instagram.com/simplifyweb/" target="_blank">Simplify Web</a>
        </div>
    </footer>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- js files (body) -->
    <?php
    foreach ($scripts_body as $script) {
        echo '<script src="' . BASE_URL . 'public/script/' . $script . '.js"></script>';
    }
    ?>
</body>

</html>