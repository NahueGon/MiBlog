<a class="" href=" <?= $user ? '/user/profile' : '/auth/login'?>">
    <div class="bg-neutral-900 w-full border-1 border-neutral-700 rounded-lg flex flex-col gap-4 overflow-hidden">
        <div class="relative w-full min-h-30 bg-cover bg-center bg-no-repeat <?= $user && $user['profile_background'] ? '' : 'bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl'?>" 
            style="<?= $user && $user['profile_background'] 
                ? 'background-image: url(/uploads/users/user_' . $user['id'] . '/' . $user['profile_background'] . ')'
                : '' ?>"></div>
        <div class="flex flex-col gap-2 px-4 pb-4 pt-6">
            <div class="relative w-full">
                <?php if($user && $user['profile_image']): ?>
                    <img class="absolute bottom-0 mt-10 w-full min-w-10 max-w-23 max-h-23 rounded-full border-6 border-neutral-900" src="<?= '/uploads/users/' . 'user_' . $user['id'] . '/' .  $user['profile_image'] ?>" alt="user photo">
                <?php else: ?>
                    <img class="absolute bottom-0 mt-10 w-full min-w-10 max-w-23 rounded-full border-6 border-neutral-900" src="/uploads/users/anon-profile.jpg" alt="user photo">
                <?php endif; ?>
            </div>
            <div class="mb-2">
                <h2 class="text-2xl sm:text-sm font-semibold">
                    <?= $user ? $user['name'] . ' ' . $user['lastname'] : 'Inicia Sesion!'?>
                </h2>
                <p href="" class="font-medium text-xs text-neutral-400">
                    <?= $user ? $user['email'] : '' ?>
                </p>
                <div class="text-neutral-400">
                    <span class="font-medium text-sm">
                        <?= ($province && $country) ? $province['name'] . ', ' . $country['name'] : '' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</a>
