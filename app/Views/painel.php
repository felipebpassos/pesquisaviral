<main>
    <div class="dashboard-header">

        <div class="translator">
            <div class="lenguage-select">
                English
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
        <div class="options">
            <?php if ($user['plan']['type'] === 'freemium') : ?>
                <a class="link-premium" href="<?php echo BASE_URL; ?>painel/plans/">
                    <button class="standout-btn go-premium">
                        <svg x="0px" y="0px" viewBox="0 0 122.88 107.76" style="enable-background:new 0 0 122.88 107.76">
                            <g>
                                <path d="M21.13,83.86h80.25l12.54-34.73c0.65,0.21,1.35,0.32,2.07,0.32c3.8,0,6.89-3.08,6.89-6.89 c0-3.8-3.08-6.89-6.89-6.89c-3.8,0-6.89,3.08-6.89,6.89c0,1.5,0.48,2.88,1.29,4.01l-7.12,5.86c-9.97,8.2-16.22,4.4-14.27-8.34 l1.1-7.17c0.38,0.07,0.78,0.1,1.18,0.1c3.8,0,6.89-3.08,6.89-6.89c0-3.8-3.08-6.89-6.89-6.89c-3.8,0-6.89,3.08-6.89,6.89 c0,2.17,1.01,4.11,2.58,5.37l-1.71,2.7c-8.38,12.58-14.56,7.76-17.03-4.67l-4.41-20.31c2.47-1.05,4.21-3.49,4.21-6.35 c0-3.8-3.08-6.89-6.89-6.89c-3.8,0-6.89,3.08-6.89,6.89c0,3.18,2.15,5.85,5.07,6.65L56.46,25.1c-2.48,10.61-5.45,31.75-18.88,13.73 l-2.19-2.98c1.73-1.25,2.86-3.29,2.86-5.59c0-3.8-3.08-6.89-6.89-6.89c-3.8,0-6.89,3.08-6.89,6.89c0,3.8,3.08,6.89,6.89,6.89 c0.53,0,1.05-0.06,1.55-0.18l0.46,4.68c0.9,6.39,2.05,15.04-5.29,14.63c-3.64-0.2-5.01-1.44-7.79-3.42l-7.94-5.63 c0.89-1.16,1.42-2.61,1.42-4.19c0-3.8-3.08-6.89-6.89-6.89c-3.8,0-6.89,3.08-6.89,6.89s3.08,6.89,6.89,6.89 c0.9,0,1.75-0.17,2.54-0.48L21.13,83.86L21.13,83.86z M21.07,93.47h80.51v14.29H21.07V93.47L21.07,93.47z" />
                            </g>
                        </svg>
                        <span>Go Premium</span>
                    </button>
                </a>
            <?php endif; ?>

            <div class="remaining-container">
                <svg class="remaining-ring" width="100" height="100">
                    <circle class="remaining-ring__circle" stroke="#FF1493" stroke-width="4" fill="transparent" r="46" cx="50" cy="50"></circle>
                    <circle class="remaining-ring__circle-full" stroke="rgba(0, 0, 0, 0.1)" stroke-width="4" fill="transparent" r="46" cx="50" cy="50"></circle>
                </svg>
                <div class="remaining-text">
                    <div class="remaining">
                        <span class="searches-left"><?php echo ($plan['max_searches'] - $user['monthly_search_count']); ?><span class="total-searches">/<?php echo $plan['max_searches']; ?></span></span>
                        <script>
                            const percentage = <?php echo round(($user['monthly_search_count'] / $plan['max_searches']) * 100); ?>;
                        </script>
                    </div>
                    <span>Searches Left</span>
                </div>
            </div>

        </div>
    </div>

    <div class="pesquisar_container">
        <div class="pesquisar">
            <input type="text" id="campoPesquisa" name="pesquisa" placeholder="Search profile">
            <button type="button" id="search-btn"><i class="fa fa-search"></i></button>
        </div>
    </div>

    <div class="searches-list-wrapper">
        <div>
            <label for="most-searched-profiles">Most searched profiles</label>
            <ul class="searches-list" id="most-searched-profiles">
                <?php foreach ($topSearches as $search) : ?>
                    <li class="search-item">
                        <div class="profile-picture">
                            <img src="<?php echo $search['profile_picture_url']; ?>">
                        </div>
                        <span class="username">@<?php echo $search['username']; ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</main>