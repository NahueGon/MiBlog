<?php if (!$posts): ?>
    <div class="bg-neutral-900 w-full border-1 border-neutral-700 rounded-lg p-4 flex flex-col gap-4">
        <p class="text-white text-base mb-2">No hay Posts para mostrar</p>
    </div>
    
<?php endif; ?>

<?php foreach ($posts as $post): ?>
    <div class="bg-neutral-900 w-full border-1 border-neutral-700 rounded-lg flex flex-col overflow-hidden">
        <div class="flex items-center gap-4 font-medium dark:text-white p-4">
            <img class="w-14 h-14 rounded-full" src="<?= $post['profile_image'] ? '/uploads/users/user_' . $post['user_id'] . '/' .  $post['profile_image'] : '/uploads/users/anon-profile.jpg' ?>" alt="">
            <div>
                <p class="text-lg"><?= $post['name'] . ' ' . $post['lastname'] ?></p>
                <p class="text-sm text-neutral-400"><?= $post['time_ago'] ?></p>
            </div>
        </div>

        <div class="">
            <?php if (!empty($post['file'])): ?>
                <img class="w-full" src="<?= '/uploads/users/user_' . $post['user_id'] . '/posts/' .  $post['file'] ?>" alt="" />
            <?php endif; ?>
        </div>
        
        <div class="p-4">
            <div class="flex gap-2 pb-4">
                <span class="text-sm font-bold"><?= $post['name'] . ' ' . $post['lastname'] ?></span>
                <p class="text-white text-sm"><?= htmlspecialchars($post['body']) ?></p>
            </div>

            <div class="pt-4 border-t rounded-b border-neutral-600">
                <div class="flex justify-between">
                    <p class="text-sm mb-4"><?= (int) $post['likes_count'] ?> me gusta</p>
                    <p class="text-sm mb-4 cursor-pointer hover:underline toggle-comments" data-post-id="<?= $post['id'] ?>">
                        <?= $post['comments_count'] . ' ' . ($post['comments_count'] == 1 ? 'comentario' : 'comentarios') ?>
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <form action="/post/toggleLike/<?= $post['id'] ?>" method="POST" class="size-6 ">
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
                        <textarea id="postComment" name="postComment" rows="1" class="block p-2.5 w-full text-sm rounded-lg border bg-neutral-700 border-neutral-600 placeholder-neutral-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Agrega un comentario..."></textarea>
                        <button class="h-10 cursor-pointer hover:text-blue-600" type="submit">
                            Comentar
                        </button>
                    </div>
                </form>

                <div id="comments-<?= $post['id'] ?>" class="hidden flex flex-col gap-4 text-sm text-white mt-4">
                    <?php foreach ($post['comments'] ?? [] as $comment): ?>
                        <div class="flex items-center gap-2">
                            <img class="w-8 h-8 rounded-full" src="<?= $comment['user_image'] ? '/uploads/users/user_' . $comment['user_id'] . '/' .  $comment['user_image'] : '/uploads/users/anon-profile.jpg' ?>" alt="">
                            <div class="w-full flex justify-between">
                                <p class="font-semibold">
                                    <span>
                                        <?= $comment['user_name'] . ' ' . $comment['user_lastname'] ?>
                                    </span>
                                    <?= htmlspecialchars($comment['content']) ?>
                                </p>
                                <span class="text-xs text-neutral-400"><?= $comment['time_ago'] ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
            </div>
        </div>


    </div>
<?php endforeach; ?>