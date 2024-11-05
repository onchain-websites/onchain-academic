</main>
<?php if (is_user_logged_in()) : ?>
    <footer class="footer bg-blue-dark">
        <p class="mb-0 text-center">Copyright © Onchain Capital 2024. All Rights Reserved</p>
        <div class="obj obj-1"></div>
    </footer>

    <div class="course-result-modal bg-blue-dark d-non modal" id="courseResultModal" style="background-color: var(--blue-dark);">
        <div class="bg-blue-dark h-100 w-100">
            <div class="container">
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <div class="searchbar-wrapper mx-auto" style="max-width: unset;">
                        <input type="text" placeholder="Busca tu contenido" id="searchInput">
                        <img src="<?= get_template_directory_uri(); ?>/assets/img/icons/search-icon.svg" alt="search-icon">
                        <div class="custom-border" style="z-index: 1;"></div>
                    </div>
                    <button class="close-btn close-modal"><img src="<?= get_template_directory_uri(); ?>/assets/img/icons/cross.svg" alt="cross-icon" width="22"
                            height="22"></button>
                </div>
                <div class="row g-3 justify-content-center">
                    <div class="col-md-8">
                        <div class="result-wrapper video-result" id="postResults" style="max-height: calc(100vh - 122px); overflow: auto;">
                            <span class="d-block text-center">No Post Found</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="logoutModal" class="modal logout-modal">
        <div class="modal-content" style="max-width: 700px;">
            <span class="d-block h5 font-gilroy-bold text-center" id="modalCourseTitle">¿Estás Seguro que quieres cerrar sesión en tu cuenta?</span>
            <div class="w-100" style="border-bottom: 1px solid #55C2FF; opacity: .3; margin: 16px 0;"></div>
            <div class="row g-2">
                <div class="col-md-6">
                    <button class="custom-btn font-gilroy-bold uppercase close-modal text-center">
                        <span class="position-relative text-gradient" style="z-index: 1;">CANCELAR</span>
                    </button>
                </div>
                <div class="col-md-6">
                    <a href="<?= wp_logout_url(home_url()) ?>" class="custom-btn custom-btn-danger font-gilroy-bold uppercase w-100 text-center">
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