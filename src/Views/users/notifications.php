<div class="w-full sm:max-w-xl md:max-w-4xl lg:max-w-7xl flex flex-col md:flex-row gap-6 py-24 mx-auto px-4">
    <div class="w-full sm:max-w-xl md:max-w-70 mx-auto md:m-0">
        <div class="flex flex-col gap-3 sticky top-24">
            <?php
                require_once __DIR__ . '/../layouts/partials/aboutMe.php'; 
            ?>

            <?php
                if($user){
                    require_once __DIR__ . '/../layouts/partials/userData.php'; 
                }
            ?>
        </div>
    </div>
    <div class="w-full sm:max-w-xl md:max-w-full lg:max-w-5xl flex flex-col lg:flex-row gap-6 mx-auto md:m-0">
        <div class="w-full sm:min-w-90 flex flex-col gap-3">
            <?php 
                require_once __DIR__ . '/partials/notifications.php'; 
            ?>
        </div>
        <div class="w-full lg:max-w-90 flex flex-col gap-3">
            <?php 
                require_once __DIR__ . '/../layouts/partials/aboutMiBlog.php'; 
            ?>

            <?php 
                require_once __DIR__ . '/../layouts/partials/footer.php'; 
            ?>
        </div>
    </div>
</div>