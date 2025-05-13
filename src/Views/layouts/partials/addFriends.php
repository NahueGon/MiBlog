<div class="bg-neutral-900 w-full border-1 border-neutral-700 rounded-lg p-4 lg:flex flex-col gap-6 hidden">
    <h3>Añadir a tus Amigos</h3>
    
    <?php foreach ($users as $addUser): ?>
        <div class="flex items-center justify-between">
            <a href="/user/publicProfile/<?= $addUser['id'] ?>">
                <div class="flex gap-3 items-center font-medium text-white">
                    <img class="w-13 h-13 rounded-full" src="<?= $addUser['profile_image'] ? '/uploads/users/user_' . $addUser['id'] . '/' . $addUser['profile_image'] : '/uploads/users/anon-profile.jpg' ?>" alt="">
                    <div class="max-w-[120px] xl:max-w-[150px]">
                        <span class="text-sm"><?= $addUser['name'] . ' ' . $addUser['lastname'] ?></span>
                        <p class="text-xs text-neutral-400 truncate"><?= $addUser['description'] ?></p>
                    </div>
                </div>
            </a>
            <button type="button" class="focus:ring-4 focus:outline-none font-medium rounded-lg text-xs px-2 py-4 text-center border-neutral-600 text-neutral-400 hover:text-white hover:bg-neutral-600 focus:ring-neutral-800 flex items-center gap-1 cursor-pointer">
                <i data-lucide="plus" class="size-5"></i>
            </button>
        </div>
    <?php endforeach; ?>

</div>