<?php $theme_url = get_template_directory_uri(); ?>
<?= get_header(); ?>

<section class="default-sec notes-sec">
    <div class="container">
        <div class="card round-20">
            <div class="main-title-wrapper">
                <h2 class="main-title mb-3">NOTAS</h2>
            </div>
            <div class="row g-3">
                <?php
                // Ensure the user is logged in
                if (!is_user_logged_in()) {
                    echo '<p>You must be logged in to view your notes.</p>';
                    return;
                }

                $current_user = wp_get_current_user();
                $user_id = $current_user->ID;

                // Query notes for the logged-in user
                $args = array(
                    'post_type' => 'note',
                    'posts_per_page' => -1, // Get all notes
                    'author' => $user_id // Filter by the current user's ID
                );

                $notes_query = new WP_Query($args);
                $displayed_courses = array(); // Array to track displayed course IDs

                if ($notes_query->have_posts()) {
                    while ($notes_query->have_posts()) {
                        $notes_query->the_post();
                        $course_id = get_post_meta(get_the_ID(), '_note_course_id', true);
                        $video_url = get_post_meta(get_the_ID(), '_note_video_url', true);
                        $note_url = get_post_meta(get_the_ID(), '_note_url', true);

                        // Display course title only once
                        if (!in_array($course_id, $displayed_courses) && $course_id) {
                            // Query the course post by ID
                            $course_args = array(
                                'post_type' => 'course', // Your custom post type
                                'p'         => $course_id, // Query the course by its ID
                            );

                            $course_query = new WP_Query($course_args);

                            if ($course_query->have_posts()) :
                                while ($course_query->have_posts()) : $course_query->the_post();
                ?>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="cursor-pointer" data-target="#notesModal" data-courseid="<?= $course_id ?>" data-course-title="<?= get_the_title(); ?>">
                                            <div class="img-wrapper mb-2 position-relative">
                                                <img src="<?= get_the_post_thumbnail_url(); ?>" alt="course-thumbnail"
                                                    class="slider-img img-fluid" width="304" height="170">
                                                
                                            </div>
                                            <span class="d-block fs-20 font-gilroy-bold"
                                                style="text-transform: uppercase;"><?= the_title(); ?></span>
                                        </div>
                                    </div>
                        <?php
                                endwhile;
                                wp_reset_postdata();

                                // Add the course ID to the displayed array
                                $displayed_courses[] = $course_id;
                            endif;
                        }

                        // Display the note content
                        ?>
                <?php
                    }
                } else {
                    echo '<p class="text-center">You have not saved any notes yet.</p>';
                }

                // Restore original Post Data
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </div>
</section>





<?= get_footer(); ?>

<!-- Modal structure -->
<div id="notesModal" class="modal notes-modal">
    <div class="modal-content" style="max-width: 700px;">
        <span class="close close-modal">&times;</span>
        <div class="d-flex justify-content-between">
            <span class="d-block h5 font-gilroy-bold" id="modalCourseTitle"></span>
            <select id="moduleSelector"></select>
        </div>
        <div id="notes-container" class="notes-container"></div>


        <div class="custom-border"></div>
    </div>
</div>