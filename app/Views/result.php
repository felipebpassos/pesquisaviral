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
            <div class="form-pesquisar">
                <label for="account-name"></label>
                <input type="text" id="campoPesquisa" name="account-name" placeholder="@username">
                <button class="btn-2" type="submit" id="search-btn">Pesquisar</button>
            </div>
        </div>

        <div class="results">
            <?php if (!empty($results)) : ?>
                <div class="profile-ig">
                    <div class="profile-ig-picture">
                        <img src="<?= $results['profile_picture_url'] ?>">
                    </div>
                    <div class="bio">
                        <div style="margin-left: 20px;">
                            <a href="https://www.instagram.com/<?= $username ?>" target="_blank" style="font-size: 22px;">@<?= $username ?>
                            </a>
                            <div style="display:flex; margin-top: 10px;">
                                <p><strong>
                                        <?= number_format($results['media_count'], 0, '', ',') ?>
                                    </strong> posts</p>
                                <p><strong>
                                        <?= number_format($results['followers_count'], 0, '', ',') ?>
                                    </strong> seguidores</p>
                                <p><strong>
                                        <?= number_format($results['follows_count'], 0, '', ',') ?>
                                    </strong> seguindo</p>
                            </div>
                        </div>
                    </div>
                    <div class="options" style="display: none;">
                        <button class="analysis-btn" data-id="<?php echo $username; ?>">
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
                            Análise
                        </button>
                        <button class="result-btn">
                            <i class="fa-solid fa-file-export"></i>
                            Exportar
                        </button>
                    </div>
                </div>

                <?php
                $totalLikes = 0;
                $totalComments = 0;
                $maxLikes = 0;
                $maxComments = 0;
                $maxER = 0;
                $totalER = 0;

                // Verifique se $results e $results['media']['data'] estão definidos
                if (isset($results['media']['data'])) {
                    $countMedia = count($results['media']['data']);

                    foreach ($results['media']['data'] as $result) {
                        // Adicione os valores aos totais
                        $totalLikes += isset($result['like_count']) ? $result['like_count'] : 0;
                        $totalComments += isset($result['comments_count']) ? $result['comments_count'] : 0;

                        // Atualize os máximos
                        $maxLikes = max($maxLikes, isset($result['like_count']) ? $result['like_count'] : 0);
                        $maxComments = max($maxComments, isset($result['comments_count']) ? $result['comments_count'] : 0);

                        // Calculo do Engagement Rate (ER) para cada post
                        $likes = isset($result['like_count']) ? $result['like_count'] : 0;
                        $comments = isset($result['comments_count']) ? $result['comments_count'] : 0;
                        $followersCount = $results['followers_count'];

                        $postER = (($likes + $comments) / $followersCount) * 100;

                        // Atualize o máximo de ER
                        $maxER = max($maxER, $postER);

                        // Adicione o ER ao total
                        $totalER += $postER;
                    }
                } else {
                    $countMedia = 0;
                }
                ?>

                <div class="container" style="transform: translateX(-10px);">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="metricBox">
                                <div class="title">
                                    <i class="fa-solid fa-heart"></i>
                                    <p>Likes</p>
                                </div>
                                <div class="row">
                                    <div class="col-4" style="text-align: center;">
                                        <h5>
                                            <?php echo formatarNumeroAbreviado($maxLikes); ?>
                                        </h5>
                                        <span>Máximo</span>
                                    </div>
                                    <div class="col-4" style="text-align: center;">
                                        <h5>
                                            <?php echo formatarNumeroAbreviado($totalLikes); ?>
                                        </h5>
                                        <span>Total</span>
                                    </div>
                                    <div class="col-4" style="text-align: center;">
                                        <h5>
                                            <?php echo ($countMedia > 0) ? formatarNumeroAbreviado($totalLikes / $countMedia) : "-"; ?>
                                        </h5>
                                        <span>Média</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="metricBox">
                                <div class="title">
                                    <i class="fa-solid fa-comments"></i>
                                    <p>Comentários</p>
                                </div>
                                <div class="row">
                                    <div class="col-4" style="text-align: center;">
                                        <h5>
                                            <?php echo formatarNumeroAbreviado($maxComments); ?>
                                        </h5>
                                        <span>Máximo</span>
                                    </div>
                                    <div class="col-4" style="text-align: center;">
                                        <h5>
                                            <?php echo formatarNumeroAbreviado($totalComments); ?>
                                        </h5>
                                        <span>Total</span>
                                    </div>
                                    <div class="col-4" style="text-align: center;">
                                        <h5>
                                            <?php echo ($countMedia > 0) ? formatarNumeroAbreviado($totalComments / $countMedia) : "-"; ?>
                                        </h5>
                                        <span>Média</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="metricBox">
                                <div class="title">
                                    <i class="fa-solid fa-users"></i>
                                    <p>Engajamento</p>
                                </div>
                                <div class="row">
                                    <div class="col-4" style="text-align: center;">
                                        <h5>
                                            <?php echo number_format($maxER, 2); ?> %
                                        </h5>
                                        <span>Máximo</span>
                                    </div>
                                    <div class="col-4" style="text-align: center;">
                                        <h5>N/A</h5>
                                        <span>Total</span>
                                    </div>
                                    <div class="col-4" style="text-align: center;">
                                        <h5>
                                            <?php echo ($countMedia > 0) ? number_format(($totalER / $countMedia), 2) : "-"; ?> %
                                        </h5>
                                        <span>Média</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php else : ?>
                <p>No results.</p>
            <?php endif; ?>

        </div>

        <div class="order-options">
            <div class="selectBox">
                <label for="order-select">Ordenar por:</label>
                <select id="order-select">
                    <option value="recent">Mais recente</option>
                    <option value="likes">Likes</option>
                    <option value="comments">Comentários</option>
                    <option value="oldest">Mais antigo</option>
                </select>
            </div>
            <div class="selectBox">
                <label for="postType-select">Tipo de post:</label>
                <select id="postType-select">
                    <option value="ALL">Qualquer</option>
                    <option value="VIDEO">Videos</option>
                    <option value="IMAGE">Fotos</option>
                    <option value="CAROUSEL_ALBUM">Carrossel</option>
                </select>
            </div>
            <div class="selectBox" style="display: none;">
                <label for="time-select">Período de tempo:</label>
                <select id="time-select">
                    <option value="always">Sempre</option>
                    <option value="week">Últimos 7 dias</option>
                    <option value="month">Últimos 30 dias</option>
                    <option value="year">Últimos 12 meses</option>
                </select>
            </div>
        </div>

    </header>

    <div class="posts">

        <?php if (isset($results['media']['data']) && !empty($results['media']['data'])) : ?>
            <div class="row" id="results-container-posts"></div>
            <div style="width:100%;">
                <button class="load-more-btn"><img src="<?php echo BASE_URL; ?>public/img/plus.png"></button>
            </div>
        <?php else : ?>
            <p>Nenhum post publicado por @<?= $username ?> ainda.
            </p>
        <?php endif; ?>

    </div>

</main>

<script>
    var results = <?= isset($results) ? json_encode($results) : '{}' ?>;

    var medias = results['media']['data'];

    // Cópia ordenada da mais recente à mais antiga
    var resultsByTime = results['media']['data'];

    // Cópia ordenada por like_count
    var resultsByLikes = results.media.data.slice().sort(function(a, b) {
        return (b.like_count || 0) - (a.like_count || 0);
    });

    // Cópia ordenada por comments_count
    var resultsByComments = results.media.data.slice().sort(function(a, b) {
        return (b.comments_count || 0) - (a.comments_count || 0);
    });
</script>

<?php

include __DIR__ . '/verifyEmail.php';
