<?= get_header(); ?>

<?php
$theme_url = get_template_directory_uri();
if (is_user_logged_in()) {
    wp_redirect(home_url()); // Redirect logged-in users to homepage or dashboard
    exit;
}
?>

<section class="auth-sec">
    <div class="container">
        <div class="card round-20">
            <h1 class="main-title h3 captialize text-center">Login TO<br> ONCHAIN CAPITAL</h1>
            <div class="w-100" style="border-bottom: 1px solid #29B0FB; margin-bottom: clamp(20px, 5vw, 30px)"></div>
            <form method="post" action="<?php echo wp_login_url(); ?>">
                <div class="input-wrapper">
                    <label for="user_login" class="form-label font-gilroy-bold">Username</label>
                    <div class="position-relative">
                        <input type="text" name="log" id="user_login" required class="form-control" placeholder="Enter your email" />
                        <div class="custom-border"></div>
                    </div>
                </div>
                <div class="input-wrapper">
                    <label for="user_pass" class="form-label font-gilroy-bold">Password</label>
                    <div class="position-relative">
                        <div class="custom-border"></div>
                        <input type="password" name="pwd" id="user_pass" required class="form-control" placeholder="Enter your password" />
                        <button type="button" id="passwordToggler"><img src="<?= $theme_url; ?>/assets/img/icons/eye.svg" alt="eye" width="17" height="14"></button>
                    </div>
                </div>
                <div class="input-wrapper">
                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                        <label class="d-flex gap-1 align-items-center">
                            <input type="checkbox" name="rememberme" value="forever"> Keep me signed in
                        </label>
                        <a href="<?php echo wp_lostpassword_url(); ?>" class="text-skyblue">Forgot your password?</a>
                    </div>
                </div>
                <button class="custom-btn font-gilroy-bold captialize" type="submit"><span class="position-relative text-gradient" style="z-index: 1;">Login</span></button>
                <input type="hidden" name="redirect_to" value="<?php echo esc_url(home_url('/')); ?>">
                <?php

                if (isset($_GET['login'])) {
                    if ($_GET['login'] == 'failed') {
                        echo '<p class="login-error" style="color: red;">Invalid username or password. Please try again.</p>';
                    } elseif ($_GET['login'] == 'empty') {
                        echo '<p class="login-error" style="color: red;">Both fields are required. Please fill in both your username and password.</p>';
                    } elseif ($_GET['login'] == 'invalid_email') {
                        echo '<p class="login-error" style="color: red;">Please enter a valid email address.</p>';
                    } elseif ($_GET['login'] == 'blocked') {
                        echo '<p class="login-error" style="color: red;">Your account has been temporarily blocked due to too many failed login attempts. Please try again later.</p>';
                    } elseif ($_GET['login'] == 'password_reset') {
                        echo '<p class="login-success" style="color: green;">Your password has been reset. Please log in with your new password.</p>';
                    } elseif ($_GET['login'] == 'loggedout') {
                        echo '<p class="login-info" style="color: blue;">You have successfully logged out.</p>';
                    } elseif ($_GET['login'] == 'no_access') {
                        echo '<p class="login-error" style="color: red;">You do not have permission to access this page. Please contact the administrator if you believe this is an error.</p>';
                    }
                }
                ?>

            </form>
        </div>
    </div>
    <div class="obj obj-1"></div>
    <div class="obj obj-2"></div>
</section>

<?= get_footer(); ?>