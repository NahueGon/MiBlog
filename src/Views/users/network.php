<div class="w-full sm:max-w-xl md:max-w-4xl lg:max-w-7xl flex flex-col md:flex-row gap-6 py-24 mx-auto px-4">
    <div class="w-full sm:max-w-xl md:max-w-70 lg:max-w-sm mx-auto md:m-0 order-2 md:order-1">
        <div class="flex flex-col gap-3 sticky top-24">
            <?php 
                require_once __DIR__ . '/partials/manageNetwork.php'; 
            ?>
            <?php 
                require_once __DIR__ . '/../layouts/partials/aboutMiBlog.php'; 
            ?>
            <?php 
                require_once __DIR__ . '/../layouts/partials/footer.php'; 
            ?>
        </div>
    </div>
    <div class="w-full min-w-70 sm:max-w-xl md:max-w-4xl flex flex-col gap-3 mx-auto md:m-0 order-1 md:order-2">
        <?php 
            require_once __DIR__ . '/partials/friendRequest.php'; 
        ?>
        <?php 
            require_once __DIR__ . '/partials/addFriends.php'; 
        ?>
    </div>
</div>