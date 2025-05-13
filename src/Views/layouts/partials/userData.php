<div class="bg-neutral-900 w-full border-1 border-neutral-700 rounded-lg flex flex-col gap-4 overflow-hidden">
    <div class="flex flex-col-reverse gap-5 p-4 text-sm">
        <a class="hover:underline flex justify-between" href="">
            Visualizaciones al perfil
            <span class="text-blue-600">
                <?= $user['views_count'] ?>
            </span>
        </a>
        <a class="hover:underline flex justify-between" href="">
            Publicaciones
            <span class="text-blue-600">
                <?= $user['posts_count'] ?>
            </span>
        </a>
        <a class="hover:underline flex justify-between" href="">
            Seguidores
            <span class="text-blue-600">
                <?= $user['posts_count'] ?>
            </span>
        </a>
        <a class="hover:underline flex justify-between" href="">
            Seguidos
            <span class="text-blue-600">
                <?= $user['posts_count'] ?>
            </span>
        </a>
    </div>
</div>