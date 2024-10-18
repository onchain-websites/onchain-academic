<?php

class OnchainTheme
{
    public $page_styles = array(
        'auth' => 'auth',
        'play' => 'course',
        'notes' => 'notes',
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
                            <?php $lesson_index = 1; // Initialize lesson count 
                            ?>
                            <?php while (have_rows('video')) : the_row(); ?>
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <div class="img-wrapper mb-2">
                                        <?php $video_thumbnail = get_sub_field('video_thumbnail'); ?>
                                        <?php if ($video_thumbnail) : ?>
                                            <img src="<?php echo esc_url($video_thumbnail['url']); ?>" alt="course-thumbnail"
                                                class="slider-img img-fluid" width="304" height="170">
                                        <?php endif; ?>
                                        <div class="custom-border"></div>
                                        <button type="button" class="play-btn video-play-btn-query" data-video="<?php the_sub_field('video_url'); ?>" data-postid="<?= get_the_ID(); ?>" data-videotitle="<?php the_sub_field('video_title'); ?>" data-modulecount="<?= $module_count; ?>" data-currentmodule="<?= $module_number; ?>" data-videothumb="<?= esc_url($video_thumbnail['url']); ?>"><img src="<?= $theme_url ?>/assets/img/icons/play.svg" alt="play-ico"></button>
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

function load_user_notes() {
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

function load_modules() {
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

