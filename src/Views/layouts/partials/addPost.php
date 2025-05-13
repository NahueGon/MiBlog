<div class="bg-neutral-900 w-full border-1 border-neutral-700 rounded-lg p-4 flex flex-col gap-4">

    <div class="flex items-center gap-4">
        <?php if($user): ?>
            <a class="w-18 rounded-full" href="/user/profile">
                <?php if($user['profile_image']): ?>
                    <img class="size-14 rounded-full" src="<?= '/uploads/users/' . 'user_' . $user['id'] . '/' .  $user['profile_image'] ?>" alt="user photo">
                <?php else: ?>
                    <img class="size-14 rounded-full max-w-20" src="/uploads/users/anon-profile.jpg" alt="user photo">
                <?php endif; ?>
            </a>
        <?php else: ?>
            <img class="size-14 rounded-full" src="/uploads/users/anon-profile.jpg" alt="user photo">
        <?php endif; ?>
        <button data-modal-target="static-modal" data-modal-toggle="static-modal" type="button" class="w-full h-full text-start p-4 text-sm font-medium focus:outline-none rounded-full border focus:z-10 focus:ring-4 focus:ring-neutral-700 bg-neutral-800 text-neutral-400 border-neutral-600 hover:text-white hover:bg-neutral-700 cursor-pointer">
            Añadir Publicacion
        </button>
    </div>

</div>