<a class="" href=" <?= isset($user) ? '/user/profile' : '/auth/login'?>">
    <div class="bg-neutral-900 w-full border-1 border-neutral-700 rounded-lg flex flex-col gap-4 overflow-hidden animate__animated animate__fadeIn animate__faster">
        <div class="relative w-full min-h-30 md:min-h-20 bg-cover bg-center bg-no-repeat <?= isset($user) && $user['background_image'] ? '' : 'bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl'?>" 
            style="<?= isset($user) && $user['background_image'] 
                ? 'background-image: url(/uploads/users/user_' . $user['id'] . '/' . $user['background_image'] . ')'
                : '' ?>"></div>
        <div class="flex flex-col gap-2 px-4 pb-4 pt-6">
            <div class="relative w-full">
                <?php if(isset($user) && $user['profile_image']): ?>
                    <img class="absolute bottom-0 mt-10 w-full min-w-10 max-w-30 max-h-30 sm:max-w-25 sm:max-h-25 rounded-full border-6 border-neutral-900" src="<?= '/uploads/users/' . 'user_' . $user['id'] . '/' .  $user['profile_image'] ?>" alt="user photo">
                <?php else: ?>
                    <img class="absolute bottom-0 mt-10 w-full min-w-10 max-w-30 max-h-30 sm:max-w-25 sm:max-h-25 rounded-full border-6 border-neutral-900" src="/uploads/users/anon-profile.jpg" alt="user photo">
                <?php endif; ?>
            </div>
            <div class="mb-2">
                <h2 class="text-3xl md:text-xl font-semibold mb-1">
                    <?= isset($user) ? $user['name'] . ' ' . $user['lastname'] : 'Inicia Sesion!'?>
                </h2>
                <p href="" class="font-medium md:text-xs text-neutral-400">
                    <?= isset($user) ? $user['email'] : '' ?>
                </p>
                <p href="" class="font-medium md:text-xs text-neutral-400">
                    <?= isset($user['province']['name'], $user['country']['name']) 
                        ? $user['province']['name'] . ', ' . $user['country']['name'] 
                        : '' 
                    ?>
                </p>
                <p href="" class="font-medium text-xs text-neutral-400 mt-1">
                    <?= isset($user) && $user['description'] ? $user['description'] : '' ?>
                </p>
            </div>
        </div>
    </div>
</a>
