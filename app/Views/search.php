<main>

    <?php if ($user['plan']['type'] === 'freemium' || $user['plan']['type'] === 'individual') : ?>
        <div class="alert-free" role="alert">
            <?php if ($user['plan']['type'] === 'freemium') : ?>
                Sua conta não é verificada, o que significa que tem limites.
            <?php endif; ?>

            <?php if (($plan['max_searches'] - $user['monthly_search_count']) > 0) : ?>
                Você ainda tem <?= ($plan['max_searches'] - $user['monthly_search_count']); ?> pesquisas restantes esse mês.
            <?php else : ?>
                Você atingiu o limite mensal máximo de pesquisas :(
            <?php endif; ?>

            <?php if ($user['plan']['type'] === 'freemium') : ?>
                <a href="#" id="verificarConta">Verificar conta</a>.
            <?php endif; ?>    
        </div>
    <?php endif; ?>

    <header>

        <div class="top-options">
            <form id="search-form" class="form-pesquisar">
                <label for="account-name"></label>
                <input type="text" id="account-name" name="account-name" placeholder="@username">
                <button class="btn-2" type="submit">Pesquisar</button>
            </form>
        </div>

    </header>

    <div id="results-container">
        <?php foreach (array_reverse($userSearches) as $username => $searchResult) : ?>
            <?php
            // Decodificar a string JSON em um array associativo
            $userData = json_decode($searchResult, true);
            ?>
            <div class="card">
                <div class="card-body">
                    <img src="<?php echo $userData['profile_picture_url']; ?>" class="card-img-top" alt="Imagem de Perfil">
                    <p class="card-text">@<?php echo $username; ?></p>
                    <div class="card-bottom">
                        <button class="result-btn" data-id="<?php echo $username; ?>"><i class="fa-solid fa-images"></i> Resultado</button>
                        <button class="analysis-btn" data-id="<?php echo $username; ?>" style="display: none;">
                            <svg width="18" height="18" viewBox="0 0 256 256" xml:space="preserve">
                                <defs>
                                </defs>
                                <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                                    <path d="M 87.994 0 H 69.342 c -1.787 0 -2.682 2.16 -1.418 3.424 l 5.795 5.795 l -33.82 33.82 L 28.056 31.196 l -3.174 -3.174 c -1.074 -1.074 -2.815 -1.074 -3.889 0 L 0.805 48.209 c -1.074 1.074 -1.074 2.815 0 3.889 l 3.174 3.174 c 1.074 1.074 2.815 1.074 3.889 0 l 15.069 -15.069 l 14.994 14.994 c 1.074 1.074 2.815 1.074 3.889 0 l 1.614 -1.614 c 0.083 -0.066 0.17 -0.125 0.247 -0.202 l 37.1 -37.1 l 5.795 5.795 C 87.84 23.34 90 22.445 90 20.658 V 2.006 C 90 0.898 89.102 0 87.994 0 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                    <path d="M 65.626 37.8 v 49.45 c 0 1.519 1.231 2.75 2.75 2.75 h 8.782 c 1.519 0 2.75 -1.231 2.75 -2.75 V 23.518 L 65.626 37.8 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                    <path d="M 47.115 56.312 V 87.25 c 0 1.519 1.231 2.75 2.75 2.75 h 8.782 c 1.519 0 2.75 -1.231 2.75 -2.75 V 42.03 L 47.115 56.312 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                    <path d="M 39.876 60.503 c -1.937 0 -3.757 -0.754 -5.127 -2.124 l -6.146 -6.145 V 87.25 c 0 1.519 1.231 2.75 2.75 2.75 h 8.782 c 1.519 0 2.75 -1.231 2.75 -2.75 V 59.844 C 41.952 60.271 40.933 60.503 39.876 60.503 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                    <path d="M 22.937 46.567 L 11.051 58.453 c -0.298 0.298 -0.621 0.562 -0.959 0.8 V 87.25 c 0 1.519 1.231 2.75 2.75 2.75 h 8.782 c 1.519 0 2.75 -1.231 2.75 -2.75 V 48.004 L 22.937 46.567 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                </g>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</main>

<?php

include __DIR__ . '/verifyEmail.php';
