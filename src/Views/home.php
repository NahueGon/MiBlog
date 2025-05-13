<div class="w-full flex flex-col md:flex-row gap-6 py-24 max-w-190 lg:max-w-250 mx-auto">
    <?php 
        require_once __DIR__ . '/layouts/partials/asideLeft.php'; 
    ?>

    <div class="w-full max-w-120 lg:max-w-230 flex flex-col lg:flex-row gap-6 mx-auto">

        <div class="w-full max-w-120 min-w-100 flex flex-col gap-3">
            <?php 
                require_once __DIR__ . '/layouts/partials/addPost.php'; 
            ?>

            <?php 
                require_once __DIR__ . '/layouts/partials/posts.php'; 
            ?>
        </div>

        <?php 
            require_once __DIR__ . '/layouts/partials/asideRight.php'; 
        ?>

    </div>
</div>

<?php 
    require_once __DIR__ . '/layouts/partials/modal.php'; 
?>
