<?php

class OnchainTheme
{
    public $page_styles = array(
        'auth' => 'auth',
        'play' => 'course',
        'notes' => 'notes',
        'profile' => 'auth',
    );

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->add_hooks();
        $this->add_theme_supports();
    }

    public function add_theme_supports()
    {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
    }

    public function add_hooks()
    {
        // Removed P tag on a contact form 7
        add_filter('wpcf7_autop_or_not', '__return_false');
        add_action('wp_enqueue_scripts', [$this, 'disable_unused_styles']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts'], 100);
        add_filter('wpcf7_form_elements', function ($content) {
            $content = preg_replace('/<(span).*?class="\s*(?:.*\s)?wpcf7-form-control-wrap(?:\s[^"]+)?\s*"[^\>]*>(.*)<\/\1>/i', '\2', $content);

            return $content;
        });
    }



    public function disable_unused_styles()
    {
        wp_dequeue_style('wp-block-library'); // WordPress core
        wp_dequeue_style('wp-block-library-theme'); // WordPress core
        wp_dequeue_style('storefront-gutenberg-blocks'); // Storefront theme
    }

    public function enqueue_scripts()
    {

        wp_enqueue_style('slick-style', get_template_directory_uri() . '/assets/css/slick/slick.css', array(), date("ymd-Gis", filemtime(get_template_directory())));
        wp_enqueue_style('slick-theme-style', get_template_directory_uri() . '/assets/css/slick/slick-theme.css', array(), date("ymd-Gis", filemtime(get_template_directory())));
        wp_enqueue_style('font', get_template_directory_uri() . '/assets/css/fonts.css', array(), date("ymd-Gis", filemtime(get_template_directory())));
        wp_enqueue_style('theme', get_template_directory_uri() . '/theme_styles.css', array(), date("ymd-Gis", filemtime(get_template_directory())));


        wp_enqueue_script('jquery', true);
        wp_enqueue_script('vimeo-player', get_template_directory_uri() . '/assets/js/vimeoplayer.js', array(), date("ymd-Gis", filemtime(get_template_directory())), true);

        wp_enqueue_script('slick-script', get_template_directory_uri() . '/assets/js/slick.js', array(), date("ymd-Gis", filemtime(get_template_directory())), true);
        wp_enqueue_script('custom', get_template_directory_uri() . '/assets/js/custom.js', array(), date("ymd-Gis", filemtime(get_template_directory())), true);

        wp_localize_script('custom', 'base_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'), // This provides the AJAX URL for the script
        ));

        if (is_front_page()) {

            wp_enqueue_style('home', get_template_directory_uri() . '/assets/css/home.css', array(), date("ymd-Gis", filemtime(get_template_directory())));
        } elseif (is_404()) {
            wp_enqueue_style('page', get_template_directory_uri() . '/assets/css/page.css', array(), date("ymd-Gis"));
        } elseif (is_single()) {
            wp_enqueue_style('single', get_template_directory_uri() . '/assets/css/course.css', array(), date("ymd-Gis", filemtime(get_template_directory())));
        } elseif (is_home()) {
            wp_enqueue_style('home', get_template_directory_uri() . '/assets/css/home.css', array(), date("ymd-Gis", filemtime(get_template_directory())));
        } else {

            $post = get_post();
            $page_styles = $this->page_styles;

            if ($post->post_name && array_key_exists($post->post_name, $page_styles)) {
                $post_name = $post->post_name;
                $css_file = $page_styles[$post_name];
                $rel_path = '/assets/css/' . $css_file . '.css';
                $dir = get_template_directory() . $rel_path;
                $uri = get_template_directory_uri() . $rel_path;
                $version = date("ymd-Gis", filemtime($dir));
                wp_enqueue_style($post_name, $uri, array(), $version);
            };
        }
    }
}
new OnchainTheme();

function custom_login_redirect_based_on_role($redirect_to, $request, $user)
{
    // Make sure a user object is passed
    if (isset($user->roles) && is_array($user->roles)) {
        // If the user is an administrator, let them go to the dashboard
        if (in_array('administrator', $user->roles)) {
            return admin_url(); // WordPress dashboard
        }

        // If the user is a maintainer, redirect them to the squadon page
        if (in_array('subscriber', $user->roles)) {
            return home_url(); // Redirect to the squadon page
        }
    }

    // Fallback: if the user role is not matched, send them to the homepage
    return home_url();
}
add_filter('login_redirect', 'custom_login_redirect_based_on_role', 10, 3);

function custom_login_failed($username)
{
    $referrer = wp_get_referer();
    if ($referrer && !strstr($referrer, 'wp-login') && !strstr($referrer, 'wp-admin')) {
        wp_redirect($referrer . '?login=failed'); // Append login failed query to the URL
        exit;
    }
}
add_action('wp_login_failed', 'custom_login_failed');



// Custom redirect after failed login
function custom_login_failed_redirect()
{
    $login_page = home_url('/auth'); // Your login page URL (front page in this case)
    $query_params = $_SERVER['QUERY_STRING'];

    // Check if login has failed
    if (strpos($query_params, 'login=failed') === false) {
        // If login has failed, add the parameter
        wp_safe_redirect(home_url('/auth'));
        wp_redirect($login_page . '?login=failed');
    } else {
        // Redirect to login page without re-adding the parameter
        wp_redirect($login_page);
    }
    exit;
}
add_action('wp_login_failed', 'custom_login_failed_redirect');

// Check if user is trying to log in with empty fields
function custom_empty_login_check($user, $username, $password)
{
    if (empty($username) || empty($password)) {
        $login_page = home_url('/auth'); // Your login page URL
        wp_redirect($login_page . '?login=empty');
        exit;
    }
    return $user;
}
add_filter('authenticate', 'custom_empty_login_check', 30, 3);

// Clear failed login parameters on successful login
function custom_clear_login_query()
{
    if (isset($_GET['login'])) {
        wp_safe_redirect(home_url()); // Redirect to front page without the query
        exit;
    }
}
add_action('wp_login', 'custom_clear_login_query');

add_action('template_redirect', 'redirect_to_auth_if_not_logged_in');
function redirect_to_auth_if_not_logged_in()
{
    if (!is_user_logged_in() && !is_page('auth')) {
        wp_redirect(home_url('/auth'));
        exit();
    }
}


add_action('admin_init', 'restrict_wp_admin_access');
function restrict_wp_admin_access()
{
    if (!current_user_can('administrator') && !wp_doing_ajax()) {
        wp_redirect(home_url());
        exit;
    }
}



// toolbar visblity start

add_action('after_setup_theme', 'disable_toolbar_for_non_admins');
function disable_toolbar_for_non_admins()
{
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}
// toolbar visiblity end



// custom post type start xxxxxxxxxxxxxxxxxxxxxxxx

function create_custom_post_type()
{
    $labels = array(
        'name' => __('Courses'),
        'singular_name' => __('Course')
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'rewrite' => array(
            'slug' => 'course',
            'with_front' => false,
        ),
        'supports' => array('title', 'editor', 'thumbnail'),
        'taxonomies' => array('category')
    );

    register_post_type('course', $args);
}
add_action('init', 'create_custom_post_type');



// load post using ajax
function load_more_courses_ajax_handler()
{
    $theme_url = get_template_directory_uri();
    // Check if a valid post ID is passed via AJAX
    $course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
    $module_number = isset($_POST['module_number']) ? intval($_POST['module_number']) : 0;

    if (!$course_id) {
        echo '<p>Invalid course ID.</p>';
        wp_die();
    }

    // Set up query for 'course' post type
    $args = array(
        'post_type' => 'course',
        'posts_per_page' => -1,
        'post__in'  => array($course_id) // Dynamic course ID
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
?>
            <?php if (have_rows('module')) :
                $modulei = 1;
            ?>

                <?php $module_count = 0; ?>
                <?php while (have_rows('module')) : the_row(); ?>
                    <?php $module_count++; ?>
                <?php endwhile; ?>
                <?php while (have_rows('module')) : the_row();
                    if ($modulei == $module_number) :
                ?>

                        <?php if (have_rows('video')) : ?>
                            <?php $lesson_index = 1; ?>
                            <?php while (have_rows('video')) : the_row(); ?>
                                <?php if (get_sub_field('is_video_comming_soon') == 1) : ?>
                                    <?php $postStatus = false; ?>
                                <?php else : ?>
                                    <?php $postStatus = true; ?>
                                <?php endif; ?>
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <div class="img-wrapper mb-2">
                                        <?php if ($postStatus) : ?>
                                        <?php else : ?>
                                            <span class="d-block fs-14" style="position: absolute; z-index: 2; top: 10px; right: 10px; background-color: var(--skyblue); color: var(--navy-blue); padding: 2px 6px; border-radius: 6px;">Muy pronto</span>
                                        <?php endif; ?>
                                        <?php $video_thumbnail = get_sub_field('video_thumbnail'); ?>
                                        <?php if ($video_thumbnail['url']) : ?>
                                            <img src="<?php echo esc_url($video_thumbnail['url']); ?>" alt="course-thumbnail"
                                                class="slider-img img-fluid" width="304" height="170">
                                        <?php else : ?>
                                            <img src="<?= $theme_url; ?>/assets/img/course-no-image-placeholder.webp" alt="course-thumbnail"
                                                class="slider-img img-fluid" width="304" height="170">
                                        <?php endif; ?>

                                        <button type="button" class="play-btn video-play-btn-query" data-video="<?php if ($postStatus) : ?><?php the_sub_field('video_url'); ?><?php endif; ?>" data-postid="<?= get_the_ID(); ?>" data-videotitle="<?php the_sub_field('video_title'); ?>" data-modulecount="<?= $module_count; ?>" data-currentmodule="<?= $module_number; ?>" data-videothumb="<?= esc_url($video_thumbnail['url']); ?>" <?php if (!$postStatus) : ?>disabled<?php endif; ?>><img src="<?= $theme_url ?>/assets/img/icons/play.svg" alt="play-ico"></button>
                                    </div>
                                    <span class="d-block fs-20 font-gilroy-bold" style="text-transform: uppercase;">L-<?= $lesson_index; ?>: <?php the_sub_field('video_title'); ?></span>
                                    <?php $lesson_index++; ?>
                                </div>
                            <?php endwhile; ?>
                        <?php endif; ?>

                <?php
                    endif;
                    $modulei++;
                endwhile; ?>
            <?php endif; ?>
        <?php
        endwhile;
        wp_reset_postdata();
    else :
        echo '<p>No posts found.</p>';
    endif;

    wp_die(); // Always include this to properly terminate the AJAX request
}
add_action('wp_ajax_load_more_courses', 'load_more_courses_ajax_handler');
add_action('wp_ajax_nopriv_load_more_courses', 'load_more_courses_ajax_handler');


// Register AJAX actions for both logged-in and non-logged-in users
add_action('wp_ajax_load_more_courses', 'load_more_courses_ajax_handler');
add_action('wp_ajax_nopriv_load_more_courses', 'load_more_courses_ajax_handler');



// course custom post type endxxxxxxxxxxxxxxxxxxxxxxxxxx


// notes custom post type start xxxxxxxxxxxxxxxxxxxxxxxxxxx
function create_notes_post_type()
{
    $labels = array(
        'name' => __('Notes'),
        'singular_name' => __('Note'),
    );

    $args = array(
        'labels' => $labels,
        'public' => false,  // Make the post type private
        'show_ui' => true,  // Show in the admin interface
        'exclude_from_search' => true,
        'supports' => array('title', 'editor'), // Only need title and content
        'capability_type' => 'post',
        'map_meta_cap' => true,
    );

    register_post_type('note', $args);
}
add_action('init', 'create_notes_post_type');

// Add Meta Fields for Course ID and Video URL
function add_notes_meta_boxes()
{
    add_meta_box('note_course_id', 'Course ID', 'note_course_id_callback', 'note', 'side', 'default');
    add_meta_box('note_video_url', 'Video URL', 'note_video_url_callback', 'note', 'side', 'default');
    add_meta_box('note_video_title', 'Video Title', 'note_video_title_callback', 'note', 'side', 'default');
    add_meta_box('note_current_module', 'Current Module', 'note_current_module_callback', 'note', 'side', 'default');
    add_meta_box('note_url', 'URL', 'note_url_callback', 'note', 'side', 'default');
}
add_action('add_meta_boxes', 'add_notes_meta_boxes');

function note_course_id_callback($post)
{
    $value = get_post_meta($post->ID, '_note_course_id', true);
    echo '<input type="number" name="note_course_id" value="' . esc_attr($value) . '" />';
}

function note_video_url_callback($post)
{
    $value = get_post_meta($post->ID, '_note_video_url', true);
    echo '<input type="url" name="note_video_url" value="' . esc_attr($value) . '" />';
}
function note_video_title_callback($post)
{
    $value = get_post_meta($post->ID, '_note_video_title', true);
    echo '<input type="text" name="note_video_title" value="' . esc_attr($value) . '" />';
}
function note_current_module_callback($post)
{
    $value = get_post_meta($post->ID, '_note_current_module', true);
    echo '<input type="number" name="note_current_module" value="' . esc_attr($value) . '" />';
}
function note_url_callback($post)
{
    $value = get_post_meta($post->ID, '_note_url', true);
    echo '<input type="url" name="note_url" value="' . esc_attr($value) . '" />';
}
function save_note_meta_boxes($post_id)
{
    if (array_key_exists('note_course_id', $_POST)) {
        update_post_meta($post_id, '_note_course_id', $_POST['note_course_id']);
    }

    if (array_key_exists('note_video_url', $_POST)) {
        update_post_meta($post_id, '_note_video_url', $_POST['note_video_url']);
    }
    if (array_key_exists('note_video_title', $_POST)) {
        update_post_meta($post_id, '_note_video_title', $_POST['note_video_title']);
    }
    if (array_key_exists('note_current_module', $_POST)) {
        update_post_meta($post_id, '_note_current_module', $_POST['note_current_module']);
    }

    if (array_key_exists('note_url', $_POST)) {
        update_post_meta($post_id, '_note_url', $_POST['note_url']);
    }
}
add_action('save_post', 'save_note_meta_boxes');


// modal data fetch start

function load_user_notes()
{
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error('You must be logged in to view notes.');
    }

    $user_id = get_current_user_id();
    $course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
    $current_module = isset($_POST['current_module']) ? sanitize_text_field($_POST['current_module']) : '';

    $meta_query = array(
        'relation' => 'AND',
        array(
            'key' => '_note_course_id',
            'value' => $course_id,
            'compare' => '='
        ),
    );

    // If current_module is set, add it to the meta query
    if (!empty($current_module)) {
        $meta_query[] = array(
            'key' => '_note_current_module',
            'value' => $current_module,
            'compare' => '='
        );
    }

    // Query notes for the logged-in user
    $args = array(
        'post_type' => 'note',
        'posts_per_page' => -1, // Get all notes
        'author' => $user_id,
        'meta_query' => $meta_query // Filter by the current user's ID
    );

    $notes_query = new WP_Query($args);
    $notes = array();

    if ($notes_query->have_posts()) {
        while ($notes_query->have_posts()) {
            $notes_query->the_post();

            // Get note data
            $notes[] = array(
                'note_id' => get_the_ID(),
                'title' => get_the_title(),
                'content' => get_the_content(),
                'course_id' => get_post_meta(get_the_ID(), '_note_course_id', true),
                'video_title' => get_post_meta(get_the_ID(), '_note_video_title', true),
                'url' => get_post_meta(get_the_ID(), '_note_url', true),
            );
        }
        wp_reset_postdata();
    }

    wp_send_json_success($notes);
}
add_action('wp_ajax_load_user_notes', 'load_user_notes');
add_action('wp_ajax_nopriv_load_user_notes', 'load_user_notes');

add_action('wp_ajax_load_modules', 'load_modules');

function load_modules()
{
    // Check if course ID is provided
    if (isset($_POST['course_id'])) {
        $course_id = intval($_POST['course_id']);
        $user_id = get_current_user_id();

        // Query notes for the logged-in user filtered by course ID
        $args = array(
            'post_type' => 'note',
            'posts_per_page' => -1,
            'author' => $user_id,
            'meta_query' => array(
                array(
                    'key' => '_note_course_id',
                    'value' => $course_id,
                    'compare' => '='
                )
            )
        );

        $notes_query = new WP_Query($args);
        $modules = array();

        if ($notes_query->have_posts()) {
            while ($notes_query->have_posts()) {
                $notes_query->the_post();
                $current_module = get_post_meta(get_the_ID(), '_note_current_module', true);
                if ($current_module && !in_array($current_module, $modules)) {
                    $modules[] = $current_module; // Add to modules array if not already present
                }
            }
        }

        // Restore original Post Data
        wp_reset_postdata();

        sort($modules);
        // Send back the unique modules as JSON response
        wp_send_json_success(array('modules' => $modules));
    } else {
        wp_send_json_error('Course ID not provided.');
    }
}


// profile start

function custom_profile_image_and_name_upload_form()
{
    if (is_user_logged_in()) {
        // Get current user data
        $user_id = get_current_user_id();
        $user_info = get_userdata($user_id);
        $email = $user_info->user_email;
        $profile_image = get_user_meta($user_id, 'profile_image', true); // Fetch existing profile image URL
        $first_name = $user_info->first_name;
        $last_name = $user_info->last_name;
        $full_name = trim($first_name . ' ' . $last_name); // Combine first and last name
        $theme_url = get_template_directory_uri();

        // HTML form to upload profile image and update full name
        ob_start();
        ?>
        <form id="profile-image-upload-form" method="post" enctype="multipart/form-data">
            <div class="d-flex justify-content-center">
                <label class="profile-img-wrapper">
                    <?php if ($profile_image): ?>
                        <img src="<?php echo esc_url($profile_image); ?>" alt="Profile Image" width="108" height="108" class="profile-pic">
                    <?php else: ?>
                        <img src="<?= $theme_url; ?>/assets/img/dummy-user.webp" alt="profile-pic" class="profile-pic" width="108"
                            height="108">
                    <?php endif; ?>
                    <img src="<?= $theme_url; ?>/assets/img/icons/camera-ico.webp" alt="camera-ico" class="camera-icon" width="43" height="43">
                    <input type="file" name="profile_image" id="profile_image" accept="image/*" hidden>
                </label>
            </div>
            <div class="input-wrapper">
                <label for="full_name" class="form-label font-gilroy-bold">Nombre Completo</label>
                <div class="position-relative">
                    <input type="text" name="full_name" id="full_name" class="form-control" value="<?php echo esc_attr($full_name); ?>"><br>

                </div>
            </div>
            <div class="input-wrapper">
                <label for="email" class="form-label font-gilroy-bold">Email Address</label>
                <div class="position-relative">
                    <input type="email" id="email" class="form-control" value="<?php echo esc_attr($email); ?>" style="cursor: no-drop;" disabled>

                </div>
            </div>

            <button class="custom-btn font-gilroy-bold uppercase" type="submit" name="upload_profile_data">
                <span class="position-relative text-gradient" style="z-index: 1;">ACTUALIZAR DATOS PERSONALES</span>
            </button>
        </form>

    <?php
        return ob_get_clean();
    } else {
        return '<p>You must be logged in to update your profile.</p>';
    }
}
add_shortcode('profile_image_and_fullname_upload_form', 'custom_profile_image_and_name_upload_form');


function handle_profile_data_with_fullname_upload()
{
    if (isset($_POST['upload_profile_data'])) {
        // Check if the user is logged in
        if (!is_user_logged_in()) {
            return;
        }

        $user_id = get_current_user_id();

        // Update first name and last name from full name
        if (isset($_POST['full_name'])) {
            $full_name = sanitize_text_field($_POST['full_name']);

            // Split full name into first and last name
            $name_parts = explode(' ', $full_name);
            $first_name = array_shift($name_parts); // First part of the name
            $last_name = implode(' ', $name_parts); // Remaining parts as last name

            wp_update_user(array(
                'ID' => $user_id,
                'first_name' => $first_name,
                'last_name' => $last_name
            ));
        }

        // Handle the profile image upload
        if (isset($_FILES['profile_image']) && !empty($_FILES['profile_image']['name'])) {
            if (!function_exists('wp_handle_upload')) {
                require_once(ABSPATH . 'wp-admin/includes/file.php');
            }

            $uploadedfile = $_FILES['profile_image'];
            $upload_overrides = array('test_form' => false);

            // Upload the image and get the upload data
            $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

            if ($movefile && !isset($movefile['error'])) {
                // Save the image URL in user meta
                update_user_meta($user_id, 'profile_image', $movefile['url']);
            } else {
                // Handle the error
                echo "Error uploading file: " . $movefile['error'];
            }
        }
    }
}
add_action('init', 'handle_profile_data_with_fullname_upload');


function custom_password_update_form()
{
    if (is_user_logged_in()) {
        ob_start();
    ?>
        <form id="password-update-form" method="post">
            <div class="input-wrapper">
                <label for="current_password" class="form-label">Contraseña Actual</label>
                <div class="position-relative">
                    <input type="password" name="current_password" id="current_password" class="form-control" required placeholder="Introduce Contraseña Actual">

                </div>
            </div>

            <div class="input-wrapper">
                <label for="new_password" class="form-label">Nueva Contraseña</label>
                <div class="position-relative">
                    <input type="password" name="new_password" id="new_password" class="form-control" required placeholder="Introduce Nueva Contraseña">

                </div>
            </div>

            <div class="input-wrapper">
                <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                <div class="position-relative">
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required placeholder="Confirma Nueva Contraseña">

                </div>

            </div>

            <button class="custom-btn font-gilroy-bold uppercase" type="submit" name="update_password">
                <span class="position-relative text-gradient" style="z-index: 1;">ACTUALIZAR CONTRASEÑA</span>
            </button>
        </form>

<?php
        return ob_get_clean();
    } else {
        return '<p>You must be logged in to update your password.</p>';
    }
}
add_shortcode('password_update_form', 'custom_password_update_form');

function handle_password_update()
{
    if (isset($_POST['update_password'])) {
        // Check if the user is logged in
        if (!is_user_logged_in()) {
            return;
        }

        $user_id = get_current_user_id();
        $user = get_userdata($user_id);

        // Get the posted data
        $current_password = sanitize_text_field($_POST['current_password']);
        $new_password = sanitize_text_field($_POST['new_password']);
        $confirm_password = sanitize_text_field($_POST['confirm_password']);

        // Verify current password
        if (!wp_check_password($current_password, $user->data->user_pass, $user_id)) {
            echo 'Current password is incorrect.';
            return;
        }

        // Check if the new password and confirm password match
        if ($new_password !== $confirm_password) {
            echo 'New passwords do not match.';
            return;
        }

        // Update the user's password
        wp_set_password($new_password, $user_id);
        echo 'Password successfully updated.';

        // Optionally, log the user out to force them to re-login
        wp_logout();
        wp_redirect(wp_login_url());
        exit();
    }
}
add_action('init', 'handle_password_update');



function load_posts_ajax()
{
    // Get the search query from the AJAX request
    $search_query = isset($_POST['search_query']) ? sanitize_text_field($_POST['search_query']) : '';

    // Query parameters for the main posts
    $args = array(
        'post_type' => 'course',
        'posts_per_page' => -1,
    );

    // Custom query
    $query = new WP_Query($args);
    $output = '';

    // Loop through posts
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $found = false;
            $videos_output = '';
            $module_count = 0;
            $current_module = 0;
            // $moduleLoopIndex = 1;
            while (have_rows('module')) : the_row();
                $module_count++;
            endwhile;

            // Check ACF repeater fields for matching 'video_title'
            if (have_rows('module')) {
                while (have_rows('module')) : the_row();
                    // $module_count++;

                    if (!$found) {
                        $current_module++;
                    }
                    if (have_rows('video')) {
                        while (have_rows('video')) : the_row();
                            $video_title = get_sub_field('video_title');
                            if (stripos($video_title, $search_query) !== false) {
                                $found = true; // Found a matching video title

                                // Retrieve other video details
                                $video_thumbnail = get_sub_field('video_thumbnail');
                                $video_url = get_sub_field('video_url');


                                $videos_output .= '<a href="' . home_url() . '/play/?video=' . $video_url . '&postid=' . get_the_ID() . '&videotitle=' . urlencode(esc_html($video_title)) . '&modulecount=' . $module_count . '&currentmodule=' . $current_module . '&videothumb=' . urlencode(home_url($video_thumbnail['url'])) . '" class="d-block round-20 mb-2" style="background-color: #152536; padding: 10px;">';
                                $videos_output .= '<div class="row g-3"><div class="col-lg-4"><div class="img-wrapper w-100">';
                                $videos_output .= '<img src="' . esc_url($video_thumbnail['url']) . '">';
                                $videos_output .= '</div></div>';
                                $videos_output .= '<div class="col-lg-8"><span class="h6 text-white d-block">' . esc_html($video_title) . '</span>';
                                $videos_output .= '<p class="card-text show-line-3">' . get_the_excerpt() . '</p>';
                                $videos_output .= '</div></div></a>';
                            }
                        endwhile;
                    }
                endwhile;
            }


            echo $videos_output;
        }
        wp_reset_postdata();
    } else {
        $output .= '<p>No se encontró tu búsqueda.</p>';
    }

    // Return response
    echo $output;
    wp_die(); // This is required to terminate properly
}
add_action('wp_ajax_load_posts', 'load_posts_ajax'); // For logged-in users
add_action('wp_ajax_nopriv_load_posts', 'load_posts_ajax'); // For logged-out users
