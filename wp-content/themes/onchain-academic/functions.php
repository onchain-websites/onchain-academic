<?php

class OnchainTheme
{
    public $page_styles = array(
        'auth' => 'auth',
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
        wp_enqueue_script('vimeo-player', 'https://player.vimeo.com/api/player.js', array(), date("ymd-Gis", filemtime(get_template_directory())), true);

        wp_enqueue_script('slick-script', get_template_directory_uri() . '/assets/js/slick.js', array(), date("ymd-Gis", filemtime(get_template_directory())), true);
        wp_enqueue_script('custom', get_template_directory_uri() . '/assets/js/custom.js', array(), date("ymd-Gis", filemtime(get_template_directory())), true);



        if (is_front_page()) {

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
function restrict_wp_admin_access() {
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
