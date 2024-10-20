</main>
<?php if (is_user_logged_in()) : ?>
    <footer class="footer bg-blue-dark">
        <p class="mb-0 text-center">Copyright © Onchain Capital 2024. All Rights Reserved</p>
        <div class="obj obj-1"></div>
    </footer>

    <div id="logoutModal" class="modal logout-modal">
        <div class="modal-content" style="max-width: 700px;">
            <span class="close close-modal">&times;</span>
            <span class="d-block h5 font-gilroy-bold text-center" id="modalCourseTitle">¿Estás Seguro que quieres cerrar sesión en tu cuenta?</span>
            <div class="w-100" style="border-bottom: 1px solid #55C2FF; opacity: .3; margin: 16px 0;"></div>
            <div class="row g-2">
                <div class="col-md-6">
                    <button class="custom-btn font-gilroy-bold captialize close-modal text-center">
                        <span class="position-relative text-gradient" style="z-index: 1;">CANCELAR</span>
                    </button>
                </div>
                <div class="col-md-6">
                    <a href="<?= wp_logout_url(home_url()) ?>" class="custom-btn custom-btn-danger font-gilroy-bold captialize w-100 text-center">
                        <span class="position-relative text-gradient" style="z-index: 1;">CERRAR SESIÓN</span>
                    </a>
                </div>
            </div>

            <div class="custom-border"></div>
        </div>
    </div>
<?php endif; ?>
</div>
<?= wp_footer(); ?>

</body>

</html>