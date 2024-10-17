<?php $theme_url = get_template_directory_uri(); ?>
<?= get_header(); ?>

<section class="video-sec pb-0">
    <div class="container">
        <div class="row g-3">
            <div class="col-xl-8">
                <div class="video-player-wrapper">
                    <img src="<?= get_the_post_thumbnail_url(); ?>" alt="course-thumbnail" class="img-fluid"
                        width="844" height="526">
                    <div class="custom-border"></div>
                    <a href="course-single.html" class="btn btn-light play-btn-text">Play</a>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card round-20">
                    <span class="d-block mb-2 h4 captialize">about the program</span>
                    <div class="d-flex align-items-center gap-1 flex-wrap mb-1">
                        <?php if (have_rows('course_author')) : ?>
                            <?php while (have_rows('course_author')) : the_row(); ?>
                                <div class="d-flex align-items-center" style="gap: 6px;">
                                    <img src="<?= $theme_url ?>/assets/img/icons/person.svg" alt="person-img" width="12"
                                        height="12">
                                    <a href="" class="text-skyblue fs-14"><?php the_sub_field('author_name'); ?></a>
                                </div>
                            <?php endwhile; ?>
                        <?php endif; ?>
                        <div class="d-flex align-items-center" style="gap: 6px;">
                            <img src="<?= $theme_url ?>/assets/img/icons/lesson.svg" alt="person-img" width="13"
                                height="13">
                            <a href="" class="text-skyblue fs-14"><span>25</span> Lessons</a>
                        </div>
                        <div class="d-flex align-items-center" style="gap: 6px;">
                            <img src="<?= $theme_url ?>/assets/img/icons/clock.svg" alt="person-img" width="13" height="13">
                            <a href="" class="text-skyblue fs-14">Duration: <span>3.28</span> Hours</a>
                        </div>
                    </div>
                    <p><?= the_content(); ?></p>
                    <hr class="mb-2">
                    <?php if (have_rows('course_author')) : ?>
                        <?php while (have_rows('course_author')) : the_row(); ?>
                            <?php $author_image = get_sub_field('author_image'); ?>
                            <div class="d-flex flex-wrap gap-3 mb-2">
                                <?php if ($author_image) : ?>
                                    <div class="course-author-wrapper position-relative">
                                        <img src="<?php echo esc_url($author_image['url']); ?>" alt="course-author"
                                            class="course-profile-img" width="88" height="84">
                                        <div class="custom-border"></div>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <span class="d-block font-gilroy-bold"
                                        style="font-size: clamp(18px, 4vw, 25px);"><?php the_sub_field('author_name'); ?></span>
                                    <span class="d-block"><?php the_sub_field('author_role'); ?></span>
                                </div>
                            </div>
                            <p class="mb-0"><?php the_sub_field('author_about'); ?></p>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="obj obj-2"></div>
</section>
<section class="default-sec courses-sec">
    <div class="container">
        <div class="main-title-wrapper">
            <h2 class="main-title mb-1 captialize">explora los episodios</h2>
            <p class="mb-3">Sabemos lo mejor para ti. Las mejores opciones para ti.</p>
        </div>
        <?php if (have_rows('module')) : ?>
            <?php $module_count = 0; ?>
            <?php while (have_rows('module')) : the_row(); ?>
                <?php $module_count++; ?>
            <?php endwhile; ?>
            <?php $first_module = true; ?>
            <?php while (have_rows('module') && $first_module) : the_row(); ?>
                <?php if (have_rows('video')) : ?>
                    <?php $lesson_index = 1; ?>
                    <div class="multiple-slider multiple overlay overlay-black">
                        <?php while (have_rows('video')) : the_row(); ?>
                            <div class="slider-item" title="<?php the_sub_field('video_title'); ?>">
                                <div class="img-wrapper mb-2">
                                    <?php $video_thumbnail = get_sub_field('video_thumbnail'); ?>
                                    <?php if ($video_thumbnail) : ?>
                                        <img src="<?php echo esc_url($video_thumbnail['url']); ?>" alt="course-thumbnail"
                                            class="slider-img img-fluid" width="304" height="170">
                                    <?php endif; ?>
                                    <div class="custom-border"></div>
                                    <button type="button" class="play-btn video-play-btn-query" data-video="<?php the_sub_field('video_url'); ?>" data-postid="<?= get_the_ID(); ?>" data-videotitle="<?php the_sub_field('video_title'); ?>" data-modulecount="<?= $module_count; ?>" data-currentmodule="1" data-videothumb="<?=esc_url($video_thumbnail['url']); ?>"><img src="<?= $theme_url ?>/assets/img/icons/play.svg" alt="play-ico"></button>
                                </div>
                                <span class="d-block fs-20 font-gilroy-bold" style="text-transform: uppercase;">L-<?= $lesson_index; ?>: <?php the_sub_field('video_title'); ?></span>
                            </div>
                            <?php $lesson_index++; ?>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
                <?php $first_module = false; ?>
            <?php endwhile; ?>
        <?php endif; ?>

    </div>
</section>

<script>
    jQuery(document).ready(function($) {
        $('.video-play-btn-query').each(function() {
            $(this).on('click', function() {
                var vimeoUrl = $(this).attr('data-video');
                var videoID = vimeoUrl.match(/(\d+)$/)[0];
                var videoTitle = $(this).attr('data-videotitle');
                var moduleCount = $(this).attr('data-modulecount');
                var currentModule = $(this).attr('data-currentmodule');
                var videoThumb = $(this).attr('data-videothumb');


                var postId = $(this).attr('data-postid');

                if (videoID && postId && videoTitle) {
                    window.location.href = '/play?video=' + encodeURIComponent(videoID) + '&postid=' + encodeURIComponent(postId) + '&videotitle=' + encodeURIComponent(videoTitle) + '&modulecount=' + encodeURIComponent(moduleCount)+ '&currentmodule=' + encodeURIComponent(currentModule) + '&videothumb=' + encodeURIComponent(videoThumb);
                } else {
                    alert('Video not found contact support!')
                }
            });
        });
    });
</script>

<?php get_footer(); ?>