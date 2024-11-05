<?php $theme_url = get_template_directory_uri(); ?>
<?= get_header(); ?>

<section class="default-sec notes-sec" style="min-height: calc(100vh - 200px);">
    <div class="container">
        <img src="<?= $theme_url; ?>/assets/img/404-img.webp" alt="404-img" width="438" height="267" class="img-fluid mx-auto d-block mb-3" style="max-width: 438px;">
        <h1 class="text-center main-title mb-3">The page you were looking<br>for doesn't exist!</h1>
        <a href="/" class="custom-btn mx-auto text-center" style="width: calc(100vw - 40px); max-width: 322px;">BACK TO HOMEPAGE</a>
    </div>
</section>

<?= get_footer(); ?>
