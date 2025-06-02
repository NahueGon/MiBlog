<div class="w-full bg-neutral-900 p-4 rounded-lg border border-neutral-700 animate__animated animate__fadeIn animate__faster">
    <h3 class="text-white text-xl font-semibold mb-4">Personas que podrias conocer</h3>
    <div class="w-full grid grid-cols-2 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3">
        <!-- <div class="flex justify-end px-4 pt-4">
            <button id="dropdownButton" data-dropdown-toggle="dropdown" class="inline-block text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-1.5" type="button">
                <span class="sr-only">Open dropdown</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 3">
                    <path d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z"/>
                </svg>
            </button>
            <div id="dropdown" class="z-10 hidden text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
                <ul class="py-2" aria-labelledby="dropdownButton">
                <li>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Edit</a>
                </li>
                <li>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Export Data</a>
                </li>
                <li>
                    <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                </li>
                </ul>
            </div>
        </div> -->
        <?php foreach ($users as $addUser): ?>
            <?php
                $followStatus = $addUser['follow_status'] ?? null;
                $followAction = match ($followStatus) {
                    'accepted' => 'unfollow',
                    'pending'  => 'cancel',
                    default    => 'follow',
                };
            ?>
            <div class="flex flex-col items-center p-5 w-full sm:max-w-70 border rounded-lg shadow-sm bg-neutral-800 border-neutral-700">
                <div class="flex gap-4">
                    <img class="w-18 h-18 sm: mb-3 rounded-full shadow-lg" src="<?= $addUser['profile_image'] ? '/uploads/users/user_' . $addUser['id'] . '/' . $addUser['profile_image'] : '/uploads/users/anon-profile.jpg' ?>" alt="Bonnie image"/>
                    <div>
                        <h5 class="mb-1 text-base font-medium text-white md:text-center"><?= $addUser['name'] . ' ' . $addUser['lastname'] ?></h5>
                        <span class="text-sm text-neutral-400"><?= $addUser['description'] ?: 'Sin descripción' ?></span>
                    </div>
                </div>
                <div class="flex mt-4 md:mt-6">
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
            </div>
        <?php endforeach; ?>
    </div>
</div>