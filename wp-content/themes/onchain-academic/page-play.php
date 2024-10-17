<?php $theme_url = get_template_directory_uri(); ?>
<?= get_header(); ?>

<section class="video-sec pb-0">
    <div class="container">
        <div class="row g-3">
            <div class="col-xl-8">
                <div class="video-player-wrapper vimeo-wrapper">
                    <iframe id="lesson_video" src="" frameborder="0" allow="autoplay; fullscreen; picture-in-picture; clipboard-write" style="position:absolute;top:0;left:0;width:100%;height:100%;" title=""></iframe>
                    <div class="custom-border"></div>
                </div>
            </div>
            <div class="col-xl-4">
                <?php
                // Get course ID and video URL from URL
                $course_id = isset($_GET['postid']) ? intval($_GET['postid']) : 0;
                $video_url = isset($_GET['video']) ? intval($_GET['video']) : '';
                $video_title = isset($_GET['videotitle']) ? sanitize_text_field($_GET['videotitle']) : '';
                $current_module = isset($_GET['currentmodule']) ? intval($_GET['currentmodule']) : '';
                $current_url = esc_url(home_url($_SERVER['REQUEST_URI']));
                // Check if user is logged in
                if (is_user_logged_in()) {
                    $current_user = wp_get_current_user();
                    $user_id = $current_user->ID;

                    // Query to check if the user already has a note for this course and video
                    $existing_note = new WP_Query(array(
                        'post_type' => 'note',
                        'post_status' => 'publish',
                        'author' => $user_id,
                        'meta_query' => array(
                            array(
                                'key' => '_note_course_id',
                                'value' => $course_id,
                                'compare' => '='
                            ),
                            array(
                                'key' => '_note_video_url',
                                'value' => $video_url,
                                'compare' => '='
                            )
                        )
                    ));

                    // If note exists, prefill the text area
                    $note_content = '';
                    $note_id = 0;

                    if ($existing_note->have_posts()) {
                        $existing_note->the_post();
                        $note_content = get_the_content(); // Get existing note content
                        $note_id = get_the_ID(); // Get the existing note ID for updating
                    }

                    // If the form is submitted
                    if (isset($_POST['save_note']) && check_admin_referer('save_note_action', 'save_note_nonce')) {
                        // Sanitize and validate inputs
                        $new_note_content = sanitize_textarea_field($_POST['note_content']);
                        $note_course_id = $course_id;
                        $note_video_url = $video_url;
                        $note_video_title = $video_title;
                        $note_current_module = $current_module;
                        $note_url = $current_url;

                        if ($note_id) {
                            // If a note already exists, update it
                            wp_update_post(array(
                                'ID' => $note_id,
                                'post_content' => $new_note_content
                            ));
                            echo '<p>Note updated successfully!</p>';
                        } else {
                            // If no note exists, create a new one
                            $note_id = wp_insert_post(array(
                                'post_type' => 'note',
                                'post_title' => 'Note for Course ' . $note_course_id,
                                'post_content' => $new_note_content,
                                'post_status' => 'publish',
                                'post_author' => $user_id,
                            ));

                            // Save the course ID and video URL as meta fields
                            update_post_meta($note_id, '_note_course_id', $note_course_id);
                            update_post_meta($note_id, '_note_video_url', $note_video_url);
                            update_post_meta($note_id, '_note_video_title', $note_video_title);
                            update_post_meta($note_id, '_note_current_module', $note_current_module);
                            update_post_meta($note_id, '_note_url', $note_url);
                        }
                    }

                    // Reset post data after custom query
                    wp_reset_postdata();

                    // Display the form
                ?>
                    <div class="card round-20 h-100 d-flex flex-column">
                        <span class="d-block mb-2 h6 captialize">Notes</span>
                        <hr class="mb-2 w-100">
                        <form method="post" class="d-flex flex-column gap-1" style="flex-grow: 1;">
                            <textarea name="note_content" id="note_content" rows="6" class="notes-texarea" placeholder="Write your notes here..." require><?= esc_textarea($note_content); ?></textarea>
                            <input type="hidden" name="course_id" value="<?= $course_id; ?>" />
                            <input type="hidden" name="video_url" value="<?= $video_url; ?>" />
                            <input type="hidden" name="video_title" value="<?= $video_title; ?>" />
                            <input type="hidden" name="current_module" value="<?= $current_module; ?>" />
                            <input type="hidden" name="current_url" value="<?= esc_attr($current_url); ?>" />
                            <?php wp_nonce_field('save_note_action', 'save_note_nonce'); ?>
                            <input type="submit" name="save_note" class="btn btn-blue" value="Save" />
                        </form>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap mb-2">
                    <div>
                        <h1 class="h4 mb-0 font-gilroy-bold" id="lessonTitle"></h1>
                        <p class="mb-0">Module <span id="currentModuleDisplay"></span>: Foundation</p>
                    </div>
                    <div class="d-flex gap-2 align-items-center justify-content-center flex-wrap">
                        <a href="" class="btn btn-blue"
                            style="width: calc(100vw - 40px); max-width: 322px;">Study Mode</a>
                        <a href="" class="btn btn-blue"
                            style="width: calc(100vw - 40px); max-width: 322px;">NEXT LESSON</a>
                    </div>
                </div>

                <?php
                $post_id = isset($_GET['postid']) ? intval($_GET['postid']) : 0;

                if (!$post_id) {
                    echo '<p>Invalid or missing post ID.</p>';
                    return;
                }

                // Query the specific post by ID
                $args = array(
                    'post_type' => 'course', // Change to your desired post type
                    'p'         => $post_id, // Query the post by ID
                );

                $query = new WP_Query($args);

                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post();
                ?>
                        <div class="card round-20">
                            <span class="d-block mb-2 h6 captialize">about the program</span>
                            <div class="d-flex align-items-center gap-1 flex-wrap mb-1">
                                <div class="d-flex align-items-center" style="gap: 6px;">
                                    <img src="<?= $theme_url; ?>/assets/img/icons/play-circle-ico.svg" alt="person-img" width="14"
                                        height="14">
                                    <span class="text-skyblue fs-14 d-block" id="moduleCountDisplay"></span>
                                </div>
                                <div class="d-none align-items-center" style="gap: 6px;">
                                    <img src="<?= $theme_url; ?>/assets/img/icons/clock.svg" alt="person-img" width="13" height="13">
                                    <a href="" class="text-skyblue fs-14">Duration: <span>3.28</span> Hours</a>
                                </div>
                            </div>
                            <p class="mb-0"><?php the_content(); ?></p>
                        </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p>No posts found.</p>';
                endif;
                ?>

            </div>
        </div>
    </div>
    <div class="obj obj-2"></div>
</section>
<section class="default-sec courses-sec">
    <div class="container">
        <div class="main-title-wrapper d-flex gap-2 flex-wrap align-items-center justify-content-between mb-3">
            <div>
                <h2 class="main-title mb-1 captialize">explora los episodios</h2>
                <p class="mb-0">Sabemos lo mejor para ti. Las mejores opciones para ti.</p>
            </div>
            <select id="moduleSelector"></select>
        </div>
        <div class="row g-3" id="courses-container"></div>
    </div>
</section>



<script>
    jQuery(document).ready(function($) {
        const urlParams = new URLSearchParams(window.location.search);
        const video = urlParams.get('video');
        const videoTitle = urlParams.get('videotitle');
        const postid = urlParams.get('postid');
        const videoLink = `https://player.vimeo.com/video/${video}?title=0&byline=0&portrait=0`;
        const moduleCount = urlParams.get('modulecount');
        const currentModule = urlParams.get('currentmodule');


        $('#lesson_video').attr('src', videoLink);
        $('#lessonTitle').text(videoTitle);
        $('#moduleCountDisplay').text(moduleCount >= 2 ? 'Modules: ' + moduleCount : 'Module: ' + moduleCount);
        $('#currentModuleDisplay').text(currentModule);

        for (let i = 1; i <= moduleCount; i++) {
            $('#moduleSelector').append(`<option value="${i}">Module ${i}</option>`);
        }




        let loading = false;

        function loadLessons(currentMod) {
            if (loading) return; // Prevent multiple requests
            loading = true;

            $.ajax({
                type: 'POST',
                url: base_ajax.ajax_url,
                data: {
                    action: 'load_more_courses',
                    course_id: postid,
                    module_number: currentMod
                },
                success: function(response) {
                    if (response) {
                        $('#courses-container').append(response);
                    } else {
                        $('#load-more-btn').text('No more courses').prop('disabled', true);
                    }
                    loading = false;
                },
                error: function() {
                    console.log('Error loading courses');
                    loading = false;
                }
            });
        }
        loadLessons($('#moduleSelector').val());

        function updateBtns() {
            setTimeout(() => {
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
                            window.location.href = '/play?video=' + encodeURIComponent(videoID) + '&postid=' + encodeURIComponent(postId) + '&videotitle=' + encodeURIComponent(videoTitle) + '&modulecount=' + encodeURIComponent(moduleCount) + '&currentmodule=' + encodeURIComponent(currentModule) + '&videothumb=' + encodeURIComponent(videoThumb);
                        } else {
                            alert('Video not found contact support!')
                        }
                    });
                });
            }, 1000);
        };
        $('#moduleSelector').on('change', function() {
            $('#courses-container').text('');
            loadLessons($(this).val())
            updateBtns();
        });

        updateBtns();



    });
</script>


<?= get_footer(); ?>