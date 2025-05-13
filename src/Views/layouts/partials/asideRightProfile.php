
<div class="w-full flex flex-col gap-3 max-w-130 md:max-w-170 lg:max-w-70 mx-auto sm:mx-0 animate__animated animate__fadeIn animate__faster">

    <div class="lg:flex lg:flex-col gap-3 hidden animate__animated animate__fadeIn animate__faster">
        <div class="bg-neutral-900 w-full border-1 border-neutral-700 rounded-lg p-8">
            <p>852 Seguidores</p>
            <p>931 Seguidos</p>
        </div>
    
        <div class="bg-neutral-900 w-full border-1 border-neutral-700 rounded-lg p-8">
            <p><?= $user['posts_count'] ?> Publicaciones</p>
            <p><?= $user['views_count'] ?> vistas al perfil</p>
        </div>
    </div>
        
    <?php 
        require_once __DIR__ . '/aboutMiBlog.php'; 
    ?>
</div>