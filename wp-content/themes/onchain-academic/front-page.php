<?php $theme_url = get_template_directory_uri(); ?>
<?= get_header(); ?>

<?php if (have_rows('page_builder')): ?>
    <?php while (have_rows('page_builder')) : the_row(); ?>
        <?php if (get_row_layout() == 'hero_section') : ?>
            <section class="hero-sec single-slider-sec pb-0">
                <div class="container">
                    <?php if (have_rows('slider')) : ?>
                        <div class="hero-slider single-slider single">
                            <?php while (have_rows('slider')) : the_row(); ?>
                                <?php $slide_url = get_sub_field('slide_url'); ?>
                                <a href="<?= esc_url($slide_url['url']); ?>" class="slider-item" target="<?php echo esc_attr($slide_url['target']); ?>">
                                    <?php $slide_image = get_sub_field('slide_image'); ?>
                                    <?php if ($slide_image) : ?>
                                        <img src="<?php echo esc_url($slide_image['url']); ?>" alt="<?php echo esc_attr($slide_image['alt']); ?>" class="slider-img img-fluid"
                                            width="1276" height="526">
                                    <?php endif; ?>
                                </a>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="obj obj-1"></div>
                <div class="obj obj-2"></div>
            </section>
        <?php elseif (get_row_layout() == 'featured_courses') : ?>
            <section class="default-sec courses-sec pb-0">
                <div class="container">
                    <div class="main-title-wrapper">
                        <?php if (get_sub_field('seciton_title')) : ?>
                            <h2 class="main-title mb-1 uppercase"><?php the_sub_field('seciton_title'); ?></h2>
                        <?php endif; ?>
                        <?php if (get_sub_field('section_paragraph')) : ?>
                            <p class="mb-3"><?php the_sub_field('section_paragraph'); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php $courses = get_sub_field('courses'); ?>
                    <?php if ($courses) : ?>
                        <div class="multiple-slider multiple overlay overlay-black">
                            <?php foreach ($courses as $post) : ?>
                                <?php setup_postdata($post); ?>
                                <div class="slider-item" style="<?= $post->post_status == 'publish' ? '' : 'pointer-events: none' ?>">
                                    <a href="<?= $post->post_status == 'publish' ? the_permalink() : '/' ?>">
                                        <div class="img-wrapper mb-2">
                                            <?php if (get_the_post_thumbnail_url()) : ?>
                                                <img src="<?= get_the_post_thumbnail_url(); ?>" alt="course-thumbnail"
                                                    class="slider-img img-fluid" width="304" height="170">
                                            <?php else : ?>
                                                <img src="<?= $theme_url; ?>/assets/img/course-no-image-placholder.webp" alt="course-thumbnail"
                                                    class="slider-img img-fluid" width="304" height="170">
                                            <?php endif; ?>
                                            <?php if ($post->post_status == 'publish') : ?>
                                            <?php else : ?>
                                                <span class="d-block fs-14" style="position: absolute; z-index: 2; top: 10px; right: 10px; background-color: var(--skyblue); color: var(--navy-blue); padding: 2px 6px; border-radius: 6px;">Muy pronto</span>
                                            <?php endif; ?>
                                            
                                        </div>
                                        <span class="d-block fs-20 font-gilroy-bold mb-1"
                                            style="text-transform: uppercase;"><?php the_title(); ?></span>
                                    </a>
                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                        <div class="d-flex align-items-center" style="gap: 6px;">
                                            <img src="<?= $theme_url; ?>/assets/img/icons/person.svg" alt="person-img" width="12" height="12">
                                            <span class="text-skyblue d-block"><?= get_post_meta($post->ID, 'course_author_author_name', true); ?></span>
                                        </div>
                                        <div class="d-none align-items-center" style="gap: 6px;">
                                            <img src="<?= $theme_url; ?>/assets/img/icons/lesson.svg" alt="person-img" width="13" height="13">
                                            <span class="text-skyblue d-block"><span>25</span> Lessons</span>
                                        </div>
                                    </div>
                                    <a href="<?= $post->post_status == 'publish' ? the_permalink() : '/' ?>">
                                        <p class=""><?= get_the_excerpt(); ?></p>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                            <?php wp_reset_postdata(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        <?php elseif (get_row_layout() == 'exclusive_courses') : ?>
            <section class="default-sec courses-sec pb-0">
                <div class="container">
                    <div class="main-title-wrapper">
                        <?php if (get_sub_field('seciton_title')) : ?>
                            <h2 class="main-title mb-1 uppercase"><?php the_sub_field('seciton_title'); ?></h2>
                        <?php endif; ?>
                        <?php if (get_sub_field('section_paragraph')) : ?>
                            <p class="mb-3"><?php the_sub_field('section_paragraph'); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if (have_rows('video_urls')) : ?>
                        <div class="multiple-slider multiple overlay overlay-black">
                            <?php while (have_rows('video_urls')) : the_row(); ?>
                                <div class="slider-item">
                                    <?php $url = get_sub_field('url');
                                    $query_string = parse_url($url, PHP_URL_QUERY);
                                    parse_str($query_string, $params);
                                    $videotitle = urldecode($params['videotitle']);
                                    $videothumb = urldecode($params['videothumb']);
                                    $postid = urldecode($params['postid']);

                                    $course = new WP_Query(array(
                                        'post_type' => 'course',
                                        'p' => $postid, // Query for the specific course post ID
                                    ));

                                    ?>
                                    <?php if (get_sub_field('is_coming_soon') == 1) : ?>
                                        <?php $postStatus = false; ?>
                                    <?php else : ?>
                                        <?php $postStatus = true; ?>
                                    <?php endif; ?>
                                    <a href="<?= $postStatus ? $url : '/' ?>" style="<?= $postStatus ? '' : 'pointer-events: none' ?>">
                                        <div class="img-wrapper mb-2">
                                            <img src="<?= $videothumb ?>" alt="course-thumbnail"
                                                class="slider-img img-fluid" width="304" height="170">
                                            
                                            <?php if ($postStatus) : ?>
                                            <?php else : ?>
                                                <span class="d-block fs-14" style="position: absolute; z-index: 2; top: 10px; right: 10px; background-color: var(--skyblue); color: var(--navy-blue); padding: 2px 6px; border-radius: 6px;">Muy pronto</span>
                                            <?php endif; ?>
                                        </div>
                                        <span class="d-block fs-20 font-gilroy-bold mb-1"
                                            style="text-transform: uppercase;"><?= $videotitle ?></span>
                                    </a>
                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1" style="<?= $postStatus ? '' : 'pointer-events: none' ?>">
                                        <div class="d-flex align-items-center" style="gap: 6px;">
                                            <img src="<?= $theme_url; ?>/assets/img/icons/person.svg" alt="person-img" width="12" height="12">
                                            <a href="" class="text-skyblue"><?= get_post_meta($postid, 'course_author_author_name', true); ?></a>
                                        </div>
                                        <div class="d-none align-items-center" style="gap: 6px;">
                                            <img src="<?= $theme_url; ?>/assets/img/icons/lesson.svg" alt="person-img" width="13" height="13">
                                            <a href="" class="text-skyblue"><span>25</span> Lessons</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                            <?php wp_reset_postdata(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        <?php elseif (get_row_layout() == 'recomended_section') : ?>
            <section class="default-sec recomended-sec single-slider-sec pb-0">
                <div class="container">
                    <?php if (get_sub_field('seciton_title')) : ?>
                        <h2 class="main-title mb-1 uppercase"><?php the_sub_field('seciton_title'); ?></h2>
                    <?php endif; ?>
                    <?php if (get_sub_field('section_paragraph')) : ?>
                        <p class="mb-3"><?php the_sub_field('section_paragraph'); ?></p>
                    <?php endif; ?>
                    <div class="position-relative" style="padding: 1px;">
                        <?php if (have_rows('slider')) : ?>
                            <div class="hero-slider single-slider single">
                                <?php while (have_rows('slider')) : the_row(); ?>
                                    <?php $slide_url = get_sub_field('slide_url'); ?>
                                    <a href="<?= esc_url($slide_url['url']); ?>" class="slider-item" target="<?php echo esc_attr($slide_url['target']); ?>">
                                        <?php $slide_image = get_sub_field('slide_image'); ?>
                                        <?php if ($slide_image) : ?>
                                            <img src="<?php echo esc_url($slide_image['url']); ?>" alt="<?php echo esc_attr($slide_image['alt']); ?>" class="slider-img img-fluid"
                                                width="1276" height="526">
                                        <?php endif; ?>
                                    </a>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                </div>
                <div class="obj obj-1"></div>
                <div class="obj obj-2"></div>
            </section>
        <?php elseif (get_row_layout() == 'more_money_talks') : ?>
            <section class="default-sec pb-0 money-talk-sec">
                <div class="container">
                    <div class="card round-20">
                        <div class="main-title-wrapper">
                            <?php if (get_sub_field('seciton_title')) : ?>
                                <h2 class="main-title mb-1 uppercase"><?php the_sub_field('seciton_title'); ?></h2>
                            <?php endif; ?>
                            <?php if (get_sub_field('section_paragraph')) : ?>
                                <p class="mb-3"><?php the_sub_field('section_paragraph'); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php if (have_rows('video_urls')) : ?>
                            <div class="multiple-slider multiple overlay overlay-blue">
                                <?php while (have_rows('video_urls')) : the_row(); ?>
                                    <div class="slider-item">
                                        <?php $url = get_sub_field('url');
                                        $query_string = parse_url($url, PHP_URL_QUERY);
                                        parse_str($query_string, $params);
                                        $videotitle = urldecode($params['videotitle']);
                                        $videothumb = urldecode($params['videothumb']);
                                        $postid = urldecode($params['postid']);
                                        $course = new WP_Query(array(
                                            'post_type' => 'course',
                                            'p' => $postid, // Query for the specific course post ID
                                        ));
                                        ?>
                                        <?php if (get_sub_field('is_coming_soon') == 1) : ?>
                                            <?php $postStatus = false; ?>
                                        <?php else : ?>
                                            <?php $postStatus = true; ?>
                                        <?php endif; ?>

                                        <a href="<?= $postStatus ? $url : '/' ?>" style="<?= $postStatus ? '' : 'pointer-events: none' ?>">
                                            <?php if ($videothumb) : ?>
                                                <div class="img-wrapper mb-2">
                                                    <img src="<?= $videothumb ?>" alt="course-thumbnail"
                                                        class="slider-img img-fluid" width="304" height="170">
                                                <?php else : ?>
                                                    <img src="<?= $theme_url; ?>/assets/img/course-no-image-placholder.webp" alt="course-thumbnail"
                                                        class="slider-img img-fluid" width="304" height="170">
                                                <?php endif; ?>
                                                
                                                <?php if ($postStatus) : ?>
                                                <?php else : ?>
                                                    <span class="d-block fs-14" style="position: absolute; z-index: 2; top: 10px; right: 10px; background-color: var(--skyblue); color: var(--navy-blue); padding: 2px 6px; border-radius: 6px;">Muy pronto</span>
                                                <?php endif; ?>
                                                </div>
                                                <span class="d-block fs-20 font-gilroy-bold mb-1"
                                                    style="text-transform: uppercase;"><?= $videotitle ?></span>
                                        </a>
                                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1" style="<?= $postStatus ? '' : 'pointer-events: none' ?>">
                                            <div class="d-flex align-items-center" style="gap: 6px;">
                                                <img src="<?= $theme_url; ?>/assets/img/icons/person.svg" alt="person-img" width="12" height="12">
                                                <span class="text-skyblue d-block"><?= get_post_meta($postid, 'course_author_author_name', true); ?></span>
                                            </div>
                                            <div class="d-none align-items-center" style="gap: 6px;">
                                                <img src="<?= $theme_url; ?>/assets/img/icons/lesson.svg" alt="person-img" width="13" height="13">
                                                <span class="text-skyblue d-block"><span>25</span> Lessons</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php elseif (get_row_layout() == 'continue_watching') : ?>
            <section class="default-sec courses-sec pb-0">
                <div class="container">
                    <div class="main-title-wrapper">
                        <?php if (get_sub_field('seciton_title')) : ?>
                            <h2 class="main-title mb-1 uppercase"><?php the_sub_field('seciton_title'); ?></h2>
                        <?php endif; ?>
                        <?php if (get_sub_field('section_paragraph')) : ?>
                            <p class="mb-3"><?php the_sub_field('section_paragraph'); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="three-slider multiple overlay overlay-black" id="continue-slider"></div>
                </div>
            </section>
        <?php elseif (get_row_layout() == 'coming_soon') : ?>
            <section class="default-sec courses-sec">
                <div class="container">
                    <div class="main-title-wrapper">
                        <h2 class="main-title mb-1 uppercase"><?php the_sub_field('seciton_title'); ?></h2>
                        <p class="mb-3"><?php the_sub_field('section_paragraph'); ?></p>
                    </div>
                    <?php if (have_rows('coming_slider')) : ?>
                        <div class="multiple-slider multiple overlay overlay-black">
                            <?php while (have_rows('coming_slider')) : the_row(); ?>
                                <div class="slider-item">
                                    <div class="img-wrapper">
                                        <?php $slider_image = get_sub_field('slider_image'); ?>
                                        <?php if ($slider_image) : ?>
                                            <img src="<?php echo esc_url($slider_image['url']); ?>" alt="<?php echo esc_attr($slider_image['alt']); ?>"
                                                class="slider-img img-fluid" width="304" height="387">
                                            
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>
    <?php endwhile; ?>
<?php else: ?>
    <?php // No layouts found 
    ?>
<?php endif; ?>

<script>
    jQuery(document).ready(function($) {
        // Retrieve the 'visitedUrls' array from localStorage
        let visitedUrls = JSON.parse(localStorage.getItem('visitedUrls')) || [];

        // Target the container where you want to append the slider items
        const $sliderContainer = $('#continue-slider');

        // Loop through the visited URLs array and create slider items
        $.each(visitedUrls, function(index, url) {
            let urlParams = new URLSearchParams(new URL(url).search);

            let videoThumb = urlParams.get('videothumb');
            // Create the slider-item HTML structure
            const sliderItem = `
            <div class="slider-item">
                <div class="img-wrapper mb-2">
                    <img src="${decodeURIComponent(videoThumb)}" class="slider-video d-block" width="304" height="170">
                    
                    <a href="${url}" class="play-btn"><img src="<?= $theme_url; ?>/assets/img/icons/play.svg" alt="play-ico"></a>
                </div>
            </div>
        `;

            // Append the newly created slider-item to the container
            $sliderContainer.append(sliderItem);
        });
    });
</script>

<?= get_footer(); ?>