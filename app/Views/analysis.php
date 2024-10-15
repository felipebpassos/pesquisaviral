<main>
    <?php if ($user['plan']['type'] === 'freemium') : ?>
        <div class="alert-free" role="alert">
            Your current plan is Free, which means it has search limits.
            <?php if (($plan['max_searches'] - $user['monthly_search_count']) > 0) : ?>
                You only have <?= ($plan['max_searches'] - $user['monthly_search_count']); ?> searches left.
            <?php else : ?>
                You ran out of searches :(
            <?php endif; ?>
            <a href="<?php echo BASE_URL; ?>painel/plans">Become Premium</a>.
        </div>
    <?php endif; ?>
    <h1>Analysis</h1>
</main>