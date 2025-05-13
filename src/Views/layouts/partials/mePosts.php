<?php if (!$posts): ?>
    <div class="bg-neutral-900 w-full border border-neutral-700 rounded-lg p-4 flex flex-col gap-4">
        <p class="text-white text-base mb-2">No hay Posts para mostrar</p>
    </div>
<?php endif; ?>
<div class="relative bg-neutral-900 w-full border border-neutral-700 rounded-lg flex flex-col justify-between overflow-hidden max-w-200 p-8 animate__animated animate__fadeIn animate__faster">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-2xl font-semibold">Publicaciones</h3>
        <a class="text-sm hover:underline" href="">Ver todas</a>
    </div>
    <div id="indicators-carousel" data-carousel="static" class="flex flex-nowrap gap-3 overflow-x-hidden">
        <?php foreach ($posts as $post): ?>
            <a class="" href="">
                <div class="w-75 h-full flex flex-col justify-between bg-neutral-900 border border-neutral-700 rounded-lg overflow-hidden">
                    <div class="flex items-center gap-4 font-medium text-white p-4">
                        <img class="w-14 h-14 rounded-full"
                            src="<?= $post['profile_image'] ? '/uploads/users/user_' . $post['user_id'] . '/' . $post['profile_image'] : '/uploads/users/anon-profile.jpg' ?>"
                            alt="Imagen de perfil">
                        <div>
                            <p class="text-lg"><?= htmlspecialchars($post['name'] . ' ' . $post['lastname']) ?></p>
                            <p class="text-sm text-neutral-400"><?= htmlspecialchars($post['time_ago']) ?></p>
                        </div>
                    </div>
                    <div class="h-full max-h-80 overflow-hidden">
                        <?php if (!empty($post['file'])): ?>
                            <img class="" src="<?= '/uploads/users/user_' . $post['user_id'] . '/posts/' . $post['file'] ?>" alt="Imagen del post" />
                        <?php endif; ?>
                    </div>
                    <div class="px-4 pt-4">
                        <p class="text-white text-sm">
                            <span class="font-bold"><?= htmlspecialchars($post['name'] . ' ' . $post['lastname']) ?></span>
                            <?= htmlspecialchars($post['body']) ?>
                        </p>
                    </div>
                    <div class="p-4">
                        <div class="pt-4 border-t rounded-b border-neutral-600">
                            <div class="flex justify-between">
                                <p class="text-sm mb-4"><?= (int)$post['likes_count'] ?> me gusta</p>
                                <p class="text-sm mb-4 cursor-pointer hover:underline toggle-comments" data-post-id="<?= $post['id'] ?>">
                                    <?= $post['comments_count'] . ' ' . ($post['comments_count'] == 1 ? 'comentario' : 'comentarios') ?>
                                </p>
                            </div>
                            <div class="flex items-center gap-4">
                                <form action="/post/toggleLike/<?= $post['id'] ?>" method="POST" class="size-6">
                                    <button type="submit">
                                        <i data-lucide="heart" class="size-6 cursor-pointer <?= $post['liked_by_user'] ? 'text-red-500' : '' ?>"></i>
                                    </button>
                                </form>
                                <button class="toggle-comment-form" data-post-id="<?= $post['id'] ?>">
                                    <i data-lucide="message-circle" class="size-6 cursor-pointer"></i>
                                </button>
                            </div>
                            <form id="comment-form-<?= $post['id'] ?>" class="hidden mt-4" action="/post/addComment/<?= $post['id'] ?>" method="POST">
                                <div class="flex gap-2 items-center">
                                    <textarea name="postComment" rows="1" class="block p-2.5 w-full text-sm rounded-lg border bg-neutral-700 border-neutral-600 placeholder-neutral-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Agrega un comentario..."></textarea>
                                    <button class="h-10 cursor-pointer hover:text-blue-600" type="submit">Comentar</button>
                                </div>
                            </form>
                            <div id="comments-<?= $post['id'] ?>" class="hidden flex flex-col gap-4 text-sm text-white mt-4">
                                <?php foreach ($post['comments'] ?? [] as $comment): ?>
                                    <div class="flex items-center gap-2">
                                        <img class="w-8 h-8 rounded-full"
                                            src="<?= $comment['user_image'] ? '/uploads/users/user_' . $comment['user_id'] . '/' . $comment['user_image'] : '/uploads/users/anon-profile.jpg' ?>"
                                            alt="Imagen usuario">
                                        <div class="w-full flex justify-between">
                                            <p class="font-semibold">
                                                <span><?= htmlspecialchars($comment['user_name'] . ' ' . $comment['user_lastname']) ?>:</span>
                                                <?= htmlspecialchars($comment['content']) ?>
                                            </p>
                                            <span class="text-xs text-neutral-400"><?= htmlspecialchars($comment['time_ago']) ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
    <button type="button" class="absolute top-60 start-0 h-60 z-30 flex items-center justify-center px-4 cursor-pointer group focus:outline-none" id="prev-btn">
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/70 group-hover:bg-white/90">
            <svg class="w-4 h-4 text-black rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
            </svg>
            <span class="sr-only">Previous</span>
        </span>
    </button>
    <button type="button" class="absolute top-60 end-0 h-60 z-30 flex items-center justify-center px-4 cursor-pointer group focus:outline-none" id="next-btn">
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/70 group-hover:bg-white/90">
            <svg class="w-4 h-4 text-black rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
            </svg>
            <span class="sr-only">Next</span>
        </span>
    </button>
</div>