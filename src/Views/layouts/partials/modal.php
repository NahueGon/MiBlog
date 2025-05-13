<div id="static-modal" data-modal-backdrop="static" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 bottom-0 right-0 left-0 z-50 justify-center items-center w-full">
    <div class="absolute inset-0 bg-neutral-900 opacity-50"></div>
    <div class="relative p-4 w-full h-full flex justify-center items-center animate__animated animate__fadeIn animate__faster">
        <div class="relative rounded-lg shadow-sm bg-neutral-800 z-50 w-full max-w-250">
            <div class="flex gap-5 items-center justify-between p-4 md:p-5 border-b rounded-t border-neutral-700">
                <?php if($user): ?>
                    <img class="size-14 rounded-full" src="<?= $user['profile_image'] ? '/uploads/users/' . 'user_' . $user['id'] . '/' .  $user['profile_image'] : '/uploads/users/anon-profile.jpg' ?>" alt="user photo">
                <?php else: ?>
                    <img class="size-14 rounded-full" src="/uploads/users/anon-profile.jpg" alt="user photo">
                <?php endif; ?>
               
                <div>
                    <h3 class="text-xl font-semibold text-white">
                        <?= $user ? $user['name'] . ' ' . $user['lastname'] : 'Inicia Sesion!'?>
                    </h3>
                    <p class="text-sm">
                        <?= $user ? $user['description'] : ''?>
                    </p>
                </div>
                <button type="button" class="text-neutral-400 bg-transparent rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center hover:bg-neutral-700 hover:text-white cursor-pointer" data-modal-hide="static-modal">
                    <i data-lucide="x" class="size-8"></i>
                    <span class="sr-only">Cerrar modal</span>
                </button>
            </div>
            <div class="p-6 md:p-5 space-y-4 flex flex-col sm:flex-row gap-5 justify-center items-center min-w-100">
                <form action="/post/create" method="POST" id="formPost" class="w-full" enctype="multipart/form-data">
                    <div id="postFields" class="relative m-auto overflow-hidden rounded-lg cursor-pointer group">
                        <textarea name="body" id="body" rows="6" class="block px-2.5 pb-2.5 pt-4 w-full h-100 max-h-100 text-xl bg-transparent rounded-lg border-0 appearance-none text-white focus:border-blue-500 focus:outline-none focus:ring-0 peer" placeholder="Comparte algo a tu red!"></textarea>
                        <input id="fileInput" type="file" name="fileInput" class="hidden" accept="image/*,.pdf,.doc,.docx">
                    </div>
                </form>
            </div>
            <div class="flex items-center justify-between p-4 border-t border-neutral-600">   
                <div class="flex ps-0 space-x-1 rtl:space-x-reverse sm:ps-2">
                    <button onClick="openFileInput()" type="button" class="inline-flex justify-center items-center p-2 rounded-sm cursor-pointer text-neutral-400 hover:text-white hover:bg-neutral-600">
                        <i data-lucide="paperclip" class="size-6"></i>
                        <span class="sr-only">Attach file</span>
                    </button>
                    <button onClick="openFileInput()" type="button" class="inline-flex justify-center items-center p-2 rounded-sm cursor-pointer text-neutral-400 hover:text-white hover:bg-neutral-600">
                        <i data-lucide="image" class="size-6"></i>
                        <span class="sr-only">Upload image</span>
                    </button>
                </div>
                <button form="formPost" type="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800 cursor-pointer">Enviar</button>
            </div>
        </div>
    </div>
</div>