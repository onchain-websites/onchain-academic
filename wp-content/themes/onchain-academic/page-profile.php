<?= get_header(); ?>

<?php
$theme_url = get_template_directory_uri();
?>

<section class="auth-sec">
    <div class="container">
        <div class="card round-20">
            <h1 class="main-title h3 captialize text-center">AJUSTES</h1>
            <div class="w-100" style="border-bottom: 1px solid #29B0FB; margin-bottom: clamp(20px, 5vw, 30px)"></div>
            <div class="tabs">
                <button class="tablink active" data-tab="profile">Perfil</button>
                <button class="tablink" data-tab="account">Cuenta</button>
            </div>
            <div id="profile" class="tabcontent" style="display: block;">
                <?= do_shortcode('[profile_image_and_fullname_upload_form]'); ?>
            </div>

            <div id="account" class="tabcontent">
                <?= do_shortcode('[password_update_form]') ?>
            </div>

        </div>
    </div>
    <div class="obj obj-1"></div>
    <div class="obj obj-2"></div>
</section>

<?= get_footer(); ?>

<script>
    jQuery(document).ready(function($) {
        $(".tablink").click(function() {
            var tabID = $(this).data("tab");

            // Hide all tab contents
            $(".tabcontent").hide();

            // Remove active class from all tabs
            $(".tablink").removeClass("active");

            // Show the selected tab content
            $("#" + tabID).show();

            // Add active class to the clicked tab
            $(this).addClass("active");
        });
    });
</script>