<div class="w-full max-w-120 md:max-w-70 mx-auto md:m-0">
    
    <div class="flex flex-col gap-3 sticky top-24">
        <?php
            require_once __DIR__ . '/aboutMe.php'; 
        ?>

        <?php
            if($user){
                require_once __DIR__ . '/userData.php'; 
            }
        ?>
    </div>

</div>
