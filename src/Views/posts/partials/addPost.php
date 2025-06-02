<div class="bg-neutral-900 w-full border-1 border-neutral-700 rounded-lg p-4 animate__animated animate__fadeIn animate__faster">
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
        <button onClick="toggleHidden('postFields')" type="button" class="w-full h-full text-start p-4 text-sm font-medium focus:outline-none rounded-full border focus:z-10 focus:ring-4 focus:ring-neutral-700 bg-neutral-800 text-neutral-400 border-neutral-600 hover:text-white hover:bg-neutral-700 cursor-pointer">
            Añadir Publicacion
        </button>
    </div>
    <form id="postFields" action="/post/create" method="POST" id="formPost" class="w-full mt-4 hidden animate__animated animate__fadeIn animate__faster" enctype="multipart/form-data">
        <div class="relative m-auto overflow-hidden rounded-lg cursor-pointer group mb-4">
            <textarea name="body" id="body" rows="4" minlength="3" maxlength="5000" class="block w-full p-0 h-60 min-h-40 max-h-100 text-xl bg-transparent rounded-lg border-0 appearance-none text-white focus:border-blue-500 focus:outline-none focus:ring-0 peer" placeholder="Comparte algo a tu red!"></textarea>
            <input id="file" type="file" name="file" class="hidden" accept="image/*,.pdf,.doc,.docx">
            <?php if (!empty($body)): ?>
                <p class="text-xs text-red-500 mt-1">
                    <?= htmlspecialchars($body[0]) ?>
                </p>
            <?php endif; ?>
        </div>
        <div class="flex items-center justify-between">   
            <div class="flex">
                <button onClick="openFileInput()" type="button" class="inline-flex justify-center items-center p-2 rounded-sm cursor-pointer text-neutral-400 hover:text-white hover:bg-neutral-600">
                    <i data-lucide="paperclip" class="size-6"></i>
                    <span class="sr-only">Attach file</span>
                </button>
                <button onClick="openFileInput()" type="button" class="inline-flex justify-center items-center p-2 rounded-sm cursor-pointer text-neutral-400 hover:text-white hover:bg-neutral-600">
                    <i data-lucide="image" class="size-6"></i>
                    <span class="sr-only">Upload image</span>
                </button>
            </div>
            <button type="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800 cursor-pointer">Enviar</button>
        </div>
    </form>
</div>