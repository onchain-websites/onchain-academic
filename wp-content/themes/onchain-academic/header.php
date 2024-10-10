<?php $theme_url = get_template_directory_uri();
$current_user = wp_get_current_user();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= wp_head(); ?>
    <title><?= the_title(); ?></title>
</head>

<body>
    <div style="overflow: hidden;">
        <header class="header" style="<?php if (!is_user_logged_in()) : ?>background-color: transparent;<?php endif; ?>">
            <div class="container">
                <?php if (is_user_logged_in()) : ?>
                    <nav class="navbar">
                        <div class="d-flex align-items-center justify-content-between gap-2 w-100 searchbar-main-wrapper">
                            <a href="<?= home_url(); ?>" class="navbar-brand">
                                <img src="<?= $theme_url; ?>/assets/img/logo.svg" alt='logo' width="48" height="53"
                                    style="min-width: 48px;" />
                            </a>
                            <ul class="menu">
                                <li><a href="https://x.com/0nchainCapital" target='_blank'
                                        class='d-flex align-items-center gap-1'><img
                                            src="<?= $theme_url; ?>/assets/img/icons/twitter-icon.svg" alt="twitter-icon" width="18"
                                            height="17"> TWITTER</a></li>
                                <li><a href="#" class='d-flex align-items-center gap-1' target='_blank'><img
                                            src="<?= $theme_url; ?>/assets/img/icons/instagram.svg" alt="instagram-icon" width="19"
                                            height="19">INSTAGRAM</a></li>
                            </ul>
                            <div class="searchbar-wrapper">
                                <input type="text" placeholder="Busca tu contenido">
                                <img src="<?= $theme_url; ?>/assets/img/icons/search-icon.svg" alt="search-icon">
                                <div class="custom-border"></div>
                            </div>
                        </div>
                        <div class="profile-wrapper">
                            <span class="d-block text-white font-abel" style="white-space: nowrap;">
                                <?php if ($current_user->first_name) : ?>
                                    <?= $current_user->first_name; ?> <?= $current_user->last_name; ?>
                                <?php else : ?>
                                    <?= $current_user->user_nicename; ?>
                                <?php endif; ?>
                            </span>
                            <div class="profile-img-wrapper">
                                <img src="<?= $theme_url; ?>/assets/img/dummy-user.webp" alt="profile-pic" class="profile-pic" width="42"
                                    height="42">
                            </div>
                            <div class="profile-dropdown">
                                <span class="text-white d-block font-gilroy-bold">
                                    <?php if ($current_user->first_name) : ?>
                                        <?= $current_user->first_name; ?> <?= $current_user->last_name; ?>
                                    <?php else : ?>
                                        <?= $current_user->user_nicename; ?>
                                    <?php endif; ?>
                                </span>
                                <p class="mb-2"><?= $current_user->user_email; ?></p>
                                <div class="w-100 mb-2" style="border-bottom: 1px solid #55C2FF; opacity: .3;"></div>
                                <a href="<?= wp_logout_url(home_url()) ?>" class="fw-medium text-skyblue">Logout</a>
                            </div>
                        </div>
                        <button class='menu-toggler' type='button'>
                        </button>
                    </nav>

                <?php else : ?>
                    <div class="d-flex justify-content-center">
                        <a href="https://onchaincapital.es/" class="navbar-brand">
                            <img src="<?= $theme_url; ?>/assets/img/logo.svg" alt='logo' width="48" height="53" style="min-width: 48px;" />
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </header>

        <main class="main">