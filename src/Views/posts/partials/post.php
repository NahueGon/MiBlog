<div class="bg-neutral-900 w-full border border-neutral-700 rounded-lg flex flex-col overflow-hidden animate__animated animate__fadeIn animate__faster">
    <div class="w-full p-4">
        <div class="w-full flex justify-between gap-4">
            <div class="flex gap-4 flex-grow min-w-0">
                <a href="/user/profile/<?= $post['user']['id'] ?>" class="max-w-14 w-full">
                    <img 
                        class="w-14 h-14 rounded-full object-cover shrink-0"
                        src="<?= $post['user']['profile_image'] ? '/uploads/users/user_' . $post['user']['id'] . '/' .  $post['user']['profile_image'] : '/uploads/users/anon-profile.jpg' ?>" 
                        alt="Foto de perfil"
                    >
                </a>
                <div class="flex flex-col justify-center min-w-0 w-full md:max-w-65 lg:max-w-full">
                    <a href="/user/profile/<?= $post['user']['id'] ?>" class="block">
                        <p class="font-semibold text-xl truncate"><?= $post['user']['name'] . ' ' . $post['user']['lastname'] ?></p>
                    </a>
                    <span 
                        class="text-sm text-neutral-400 truncate w-full"
                        title="<?= htmlspecialchars($post['user']['description']) ?>"
                    >
                        <?= $post['user']['description'] ?: 'Sin descripción' ?>
                    </span>
                </div>
            </div>
            <div class="flex justify-end items-start gap-4 shrink-0">
                <span class="text-xs text-neutral-400"><?= $post['time_ago'] ?></span>

                <?php if (isset($user) && $user['id'] === $post['user_id']): ?>
                    <div class="relative">
                        <button 
                            id="dropdownButton-<?= $post['id'] ?>"
                            data-dropdown-toggle="dropdown-<?= $post['id'] ?>"
                            class="text-neutral-400 text-sm cursor-pointer"
                            type="button"
                        >
                            <span class="sr-only">Open dropdown</span>
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 3">
                                <path d="M2 0a1.5 1.5 0 1 1 0 3A1.5 1.5 0 0 1 2 0Zm6.041 0a1.5 1.5 0 1 1 0 3A1.5 1.5 0 0 1 8.041 0ZM14 0a1.5 1.5 0 1 1 0 3A1.5 1.5 0 0 1 14 0Z"/>
                            </svg>
                        </button>
                        <div id="dropdown-<?= $post['id'] ?>" class="z-10 hidden text-base list-none divide-y divide-neutral-100 rounded-lg shadow-sm w-22 bg-neutral-700">
                            <ul class="py-2">
                                <li>
                                    <form action="/post/update/<?= $post['id'] ?>" method="POST">
                                        <button class="w-full text-start px-4 py-2 text-sm hover:bg-neutral-600 text-neutral-200 hover:text-white cursor-pointer">
                                            Edit
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form action="/post/delete/<?= $post['id'] ?>" method="POST">
                                        <button class="w-full text-start px-4 py-2 text-sm text-red-500 hover:bg-neutral-600 cursor-pointer" type="submit">
                                            Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php if (!empty($post['body'])): ?>
            <div class="mt-4">
                <p class="text-white text-sm"><?= htmlspecialchars($post['body']) ?></p>
            </div>
        <?php endif; ?>
    </div>
    <?php if (!empty($post['file'])): ?>
        <div>
            <img class="w-full" src="<?= '/uploads/users/user_' . $post['user_id'] . '/posts/' . $post['file'] ?>" alt="Imagen del post" />
        </div>
    <?php endif; ?>
    <div class="p-4">
        <div class="flex justify-between text-sm">
            <p><?= (int) $post['likes_count'] ?> me gusta</p>
            <p class="" data-post-id="<?= $post['id'] ?>">
                <?= $post['comments_count'] . ' ' . ($post['comments_count'] == 1 ? 'comentario' : 'comentarios') ?>
            </p>
        </div>
        <hr class="my-4 border-neutral-600">
        <div class="flex items-center gap-4 mb-4">
            <form action="/like/toggleLike/<?= $post['id'] ?>" method="POST" class="size-6">
                <button type="submit">
                    <i data-lucide="heart" class="size-6 cursor-pointer <?= $post['liked_by_user'] ? 'text-red-500' : '' ?>"></i>
                </button>
            </form>
            <button class="toggle-comment-form toggle-comments" data-post-id="<?= $post['id'] ?>">
                <i data-lucide="message-circle" class="size-6 cursor-pointer"></i>
            </button>
        </div>
        <div id="comments-<?= $post['id'] ?>" class="<?= (isset($url) && $url === '/') ? 'hidden' : '' ?> flex flex-col gap-8 text-sm text-white mt-4">
            <form action="/comment/create/<?= $post['id'] ?>" method="POST">
                <div class="flex gap-2 items-center">
                    <textarea name="postComment" rows="1" class="block p-2.5 w-full text-sm rounded-lg border bg-neutral-700 border-neutral-600 placeholder-neutral-400 text-white focus:ring-blue-500 focus:border-blue-500 max-h-30 min-h-12" placeholder="Agrega un comentario..."></textarea>
                    <button class="h-10 hover:text-blue-600" type="submit">Comentar</button>
                </div>
            </form>
            <?php include __DIR__ . '/comments.php'; ?>
        </div>
    </div>
</div>
