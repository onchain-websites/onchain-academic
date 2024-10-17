<?php $theme_url = get_template_directory_uri(); ?>
<?= get_header(); ?>

<div class="course-container">
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post(); ?>
            <h1><?php the_title(); ?></h1>
            <div class="course-content">
                <?php the_content(); ?>
            </div>
        <?php endwhile;
    endif;
    ?>
</div>

<?= get_footer(); ?>