<div class="bg-neutral-900 w-full border-1 border-neutral-700 rounded-lg p-4 hidden lg:block animate__animated animate__fadeIn animate__faster">
    <h3 class="text-xl mb-4">Añadir a tus Amigos</h3>
    <div class="flex flex-col gap-4">
        <?php foreach ($users as $addUser): ?>
            <?php
                $followStatus = $addUser['follow_status'] ?? null;
                $followAction = match ($followStatus) {
                    'accepted' => 'unfollow',
                    'pending'  => 'cancel',
                    default    => 'follow',
                };
            ?>
            <div class="w-full flex flex-col">
                <div class="flex gap-4 flex-grow min-w-0">
                    <a href="/user/profile/<?= $addUser['id'] ?>" class="w-full h-14 max-w-14">
                        <img class="w-14 h-14 rounded-full object-cover shrink-0" src="<?= $addUser['profile_image'] ? '/uploads/users/user_' . $addUser['id'] . '/' . $addUser['profile_image'] : '/uploads/users/anon-profile.jpg' ?>" alt="">
                    </a>
                    <div class="flex flex-col min-w-0 w-full">
                        <a href="/user/profile/<?= $addUser['id'] ?>" class="relative">
                            <p class="font-semibold text-md"><?= $addUser['name'] . ' ' . $addUser['lastname'] ?></p>
                            <span 
                                class="block text-sm text-neutral-400 truncate whitespace-nowrap w-full absolute"
                                title="<?= htmlspecialchars($addUser['description']) ?>">
                                <?= $addUser['description'] ?: 'Sin descripción' ?>
                            </span>
                        </a>
                    </div>
                </div>
                <form action="/follow/<?= $followAction ?>/<?= $addUser['id'] ?>" method="POST" class="ml-auto">
                    <button type="submit" class="px-3 py-2 text-sm font-medium focus:outline-none rounded-full border focus:z-10 focus:ring-4  focus:ring-neutral-700 bg-neutral-800 text-neutral-400 border-neutral-600 hover:text-white hover:bg-neutral-700 cursor-pointer flex items-center gap-2 group">
                        <?php if ($followStatus === 'accepted'): ?>
                            <span class="relative  flex items-center gap-2">
                                <i data-lucide="check" class="size-4"></i>
                                <span class="transition-opacity duration-200 group-hover:opacity-0">Siguiendo</span>
                                <span class="absolute left-1 ml-6 top-0 opacity-0 transition-opacity duration-200 group-hover:opacity-100">Dejar de seguir</span>
                            </span>
                        <?php elseif ($followStatus === 'pending'): ?>
                            <span class="relative group flex items-center gap-2">
                                <i data-lucide="clock" class="size-4"></i>
                                <span class="transition-opacity duration-200 group-hover:opacity-0">Pendiente</span>
                                <span class="absolute left-1 ml-6 opacity-0 transition-opacity duration-200 group-hover:opacity-100">Cancelar</span>
                            </span>
                        <?php else: ?>
                            <i data-lucide="plus" class="size-4"></i>
                            Seguir
                        <?php endif; ?>
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

</div>