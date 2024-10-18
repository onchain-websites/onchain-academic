<?php $theme_url = get_template_directory_uri(); ?>
<?= get_header(); ?>

<section class="default-sec course-container">
    <div class="container">
        <div class="row g-3">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post(); ?>
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <a type="button" class="play-btn video-play-btn-query" href="<?= get_permalink(); ?>">
                            <div class="img-wrapper mb-2">
                                <?php $video_thumbnail = get_sub_field('video_thumbnail'); ?>
                                <img src="<?= get_the_post_thumbnail_url(); ?>" alt="course-thumbnail"
                                    class="slider-img img-fluid" width="304" height="170">
                            </div>
                            <span class="d-block fs-20 font-gilroy-bold" style="text-transform: uppercase;"><?php the_title(); ?></span>
                        </a>
                    </div>
            <?php endwhile;
            endif;
            ?>
        </div>
    </div>

</section>

<?= get_footer(); ?>