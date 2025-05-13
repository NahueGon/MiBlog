<?php if (isset($_SESSION['show_modal']) && $_SESSION['show_modal']) : ?>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('static-modal');
            if (modal) modal.classList.remove('hidden');
        });
    </script>
    <?php unset($_SESSION['show_modal']); ?>
<?php endif; ?>

<div class="flex flex-col lg:flex-row gap-3 md:gap-6 max-w-130 md:max-w-170 lg:max-w-240 mx-auto pt-24 justify-center">
    <div class="w-full flex flex-col gap-3 max-w-130 md:max-w-170 mx-auto sm:mx-0">
        <?php 
            require_once __DIR__ . '/../layouts/partials/meProfile.php'; 
        ?>

        <?php 
            require_once __DIR__ . '/../layouts/partials/mePosts.php'; 
        ?>

        <?php 
            if ( $user['id'] === ( $currentUser['id'] ?? $user['id'] )) {
                require_once __DIR__ . '/../layouts/partials/modalProfile.php'; 
            }
        ?>
    </div>

    <?php 
        require_once __DIR__ . '/../layouts/partials/asideRightProfile.php'; 
    ?>
</div>