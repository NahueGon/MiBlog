
<div class="relative bg-neutral-900 w-full md:max-w-200 border-1 border-neutral-700 rounded-lg overflow-hidden animate__animated animate__fadeIn animate__faster">

    <div class="relative w-full min-h-30 sm:min-h-40 lg:min-h-50 bg-cover bg-center bg-no-repeat <?= $user['profile_background'] ? '' : 'bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl'?>"
        style="<?=$user['profile_background'] 
                ? 'background-image: url(/uploads/users/user_' . $user['id'] . '/' . $user['profile_background'] . ')'
                : '' ?>"
        >
        <?php if( $user['id'] === ( $currentUser['id'] ?? $user['id'] ) ): ?>
            <button onClick="openModal('backgroundImage')" data-modal-target="static-modal" data-modal-toggle="static-modal" type="button" class="text-neutral-400 hover:text-white absolute right-4 top-4 bg-neutral-800 rounded-3xl p-2 cursor-pointer">
                <i data-lucide="pen" class="size-5"></i>
            </button>
        <?php endif; ?>
    </div>
    <div class="px-8 pb-8">
        <div class="flex mb-4">
            <div class="relative w-full">
                <button onClick="openModal('image')" data-modal-target="static-modal" data-modal-toggle="static-modal" type="button" class="font-medium text-sm text-center text-neutral-400 hover:text-white flex items-center gap-1 ">
                    <?php if($user['profile_image']): ?>
                        <img  class="absolute bottom-0 mt-10 w-full min-w-20 max-w-30 sm:max-w-35 lg:max-w-45 max-h-45 rounded-full border-6 border-neutral-900 cursor-pointer" src="<?= '/uploads/users/user_' . $user['id'] . '/' .  $user['profile_image'] ?>" alt="user photo">
                    <?php else: ?>
                        <img class="absolute bottom-0 mt-10 w-full min-w-20 max-w-20 sm:max-w-35 lg:max-w-45 rounded-full border-6 border-neutral-900 cursor-pointer" src="/uploads/users/anon-profile.jpg" alt="user photo">
                    <?php endif; ?>
                    
                </button>
            </div>
            <div class="py-4 w-40 flex justify-end">
                <?php if( $user['id'] === ( $currentUser['id'] ?? $user['id'] ) ): ?>
                    <button onClick="openModal('profile')" data-modal-target="static-modal" data-modal-toggle="static-modal" type="button" class="font-medium text-sm text-center text-neutral-400 hover:text-white cursor-pointer flex items-center gap-1">
                        <i data-lucide="user-pen" class="size-5"></i>
                        Editar Perfil
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <div>
            <h2 class="text-3xl sm:text-sm font-semibold"> <?= $user['name'] . ' ' . $user['lastname']?> </h2>
            <a href="" class="font-medium text-sm text-neutral-400 hover:text-blue-600 cursor-pointer inline">
                <?= $user['email'] ?>
            </a>
            <?php if( $user['id'] === ( $currentUser['id'] ?? $user['id'] ) ): ?>
                <button onClick="openModal('location')" data-modal-target="static-modal" data-modal-toggle="static-modal" type="button" class="text-neutral-400 hover:text-white cursor-pointer flex gap-1 items-center">
                    <span class="font-medium text-sm">
                        <?= $user['province'] ? $user['province']['name'] . ', ' . $user['country']['name'] : 'Agregar Pais y Provincia' ?>
                    </span>
                    <i data-lucide="pen" class="size-4"></i>
                </button>
            <?php else: ?>
                <p class="font-medium text-sm">
                    <?= $user['province'] ?? $user['province']['name'] . ', ' . $user['country']['name'] ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="relative bg-neutral-900 w-full md:max-w-200 border-1 border-neutral-700 rounded-lg overflow-hidden animate__animated animate__fadeIn animate__faster">
    <div class="p-8">
        <?php if( $user['id'] === ( $currentUser['id'] ?? $user['id'] ) ): ?>
            <button onClick="openModal('description')" data-modal-target="static-modal" data-modal-toggle="static-modal" type="button" class="font-medium rounded-lg text-sm text-center text-neutral-400 hover:text-white cursor-pointer flex items-center gap-1 mb-2">
                <i data-lucide="notebook-pen" class="size-4"></i>
                Descripción
            </button>
            <p><?= $user['description']?></p>
        <?php else: ?>
            <h3 class="text-2xl font-semibold mb-1">Descripcion</h3>
            <p><?= $user['description'] ? $user['description'] : 'Aun no tiene descripcion!'?></p>
        <?php endif; ?>
    </div>
</div>
<div class="relative w-full flex flex-col sm:flex-row gap-3 md:max-w-200 lg:hidden animate__animated animate__fadeIn animate__faster">
    <div class="bg-neutral-900 w-full border-1 border-neutral-700 rounded-lg p-8 flex-1">
        <div class="flex gap-5 justify-between flex-wrap">
            <span>852 Seguidores</span>
            <span>931 Seguidos</span>
            <span><?= $user['posts_count'] ?> Publicaciones</span>
            <span><?= $user['views_count'] ?> vistas al perfil</span>
        </div>
    </div>
</div>