<?php foreach ($post['comments'] ?? [] as $comment): ?>
    <div class="flex flex-col gap-4 ">
        <div class="w-full flex justify-between gap-4">
            <div class="flex gap-4 flex-grow min-w-0">
                <img 
                    class="w-12 h-12 rounded-full object-cover shrink-0" 
                    src="<?= $comment['user_image'] ? '/uploads/users/user_' . $comment['user_id'] . '/' .  $comment['user_image'] : '/uploads/users/anon-profile.jpg' ?>" 
                    alt="">
                <div class="flex flex-col justify-center min-w-0 w-full md:max-w-65 lg:max-w-full">
                    <p class="font-semibold text-base">
                        <?= $comment['user_name'] . ' ' . $comment['user_lastname'] ?>
                    </p>
                    <span 
                        class="block text-sm text-neutral-400 truncate overflow-hidden whitespace-nowrap w-full"
                        title="<?= htmlspecialchars($comment['user_description']) ?>">
                        <?= $comment['user_description'] ?: 'Sin descripción' ?>
                    </span>
                </div>
            </div>
            <div class="flex justify-end items-start gap-4 flex-none shrink-0">
                <span class="text-xs text-neutral-400"><?= $comment['time_ago'] ?></span>
                <?php if ($user['id'] === $comment['user_id'] || $user['id'] === $post['user_id']): ?>
                    <button id="dropdownButton-<?= $comment['id'] ?>" data-dropdown-toggle="dropdown-<?= $comment['id'] ?>" class="inline-block text-neutral-400 text-sm cursor-pointer" type="button">
                        <span class="sr-only">Open dropdown</span>
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 3">
                            <path d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z"/>
                        </svg>
                    </button>
                    <!-- Dropdown -->
                    <div id="dropdown-<?= $comment['id'] ?>" class="z-10 hidden text-base list-none divide-y divide-neutral-100 rounded-lg shadow-sm w-22 bg-neutral-700">
                        <ul class="py-2" aria-labelledby="dropdownButton">
                            <?php if ($user['id'] === $comment['user_id']): ?>
                                <li>
                                    <a href="#" class="block px-4 py-2 text-sm hover:bg-neutral-600 text-neutral-200 hover:text-white">
                                        Edit
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li>
                                <form action="/comment/delete/<?= $comment['id'] ?>" method="POST">
                                    <button class="w-full px-4 py-2 text-sm text-red-500 hover:bg-neutral-600 cursor-pointer" type="submit">
                                        Delete
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <p class="text-sm ml-16">
            <?= $comment['content'] ?>
        </p>
    </div>
<?php endforeach; ?>