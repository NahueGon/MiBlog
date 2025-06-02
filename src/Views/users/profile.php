<div class="w-full md:max-w-4xl lg:max-w-7xl flex flex-col lg:flex-row gap-6 py-24 mx-auto px-4">
    <div class="w-full min-w-70 sm:max-w-xl md:max-w-4xl flex flex-col gap-3 mx-auto lg:m-0">
        <?php 
            require_once __DIR__ . '/partials/meProfile.php'; 
        ?>
        <?php 
            require_once __DIR__ . '/partials/mePosts.php'; 
        ?>
    </div>
    <div class="w-full sm:max-w-xl md:max-w-4xl lg:max-w-xs mx-auto lg:m-0">
        <div class="flex flex-col gap-3 sticky top-24">
            <div class="lg:flex lg:flex-col gap-3 hidden animate__animated animate__fadeIn animate__faster">
                <div class="bg-neutral-900 w-full border-1 border-neutral-700 rounded-lg p-8">
                    <p><?= $user['followers_count'] ?> Seguidores</p>
                    <p><?= $user['follows_count'] ?> Seguidos</p>
                </div>
            
                <div class="bg-neutral-900 w-full border-1 border-neutral-700 rounded-lg p-8">
                    <p><?= $user['posts_count'] ?> Publicaciones</p>
                    <p><?= $user['views_count'] ?> vistas al perfil</p>
                </div>
            </div>
            <?php 
                require_once __DIR__ . '/../layouts/partials/aboutMiBlog.php'; 
            ?>
        </div> 
    </div>    
</div>

<?php 
    if ( $user['id'] === ( $currentUser['id'] ?? $user['id'] )) {
        require_once __DIR__ . '/partials/modalProfile.php'; 
    }
?>