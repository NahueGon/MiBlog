<div class="flex flex-col gap-3 w-full max-w-4xl mx-auto bg-neutral-900 p-2 rounded-lg border border-neutral-700 animate__animated animate__fadeIn animate__faster">
    <h3 class="text-white text-xl font-semibold">Solicitudes de amistad</h3>
    <?php if (empty($pendingRequests)): ?>
        <p class="text-neutral-400">No tienes solicitudes pendientes.</p>
    <?php else: ?>
        <div class="flex sm:flex-col gap-3 ">
            <?php foreach ($pendingRequests as $request): ?>
                <div class="w-full p-4 flex flex-col justify-between sm:flex-row gap-4 flex-grow min-w-0 items-center border rounded-lg shadow-sm bg-neutral-800 border-neutral-700">
                    <div class="flex justify-center gap-4">
                        <a href="/user/profile/<?= $request['id'] ?>" class="w-full max-w-14">
                            <img class="w-14 h-14 rounded-full object-cover shrink-0" src="<?= $request['profile_image'] ? '/uploads/users/user_' . $request['id'] . '/' .  $request['profile_image'] : '/uploads/users/anon-profile.jpg' ?>" alt="">
                        </a>
                        <div class="flex flex-col justify-center min-w-0 w-full md:max-w-65 lg:max-w-full sm:text-start">
                            <a href="/user/profile/<?= $request['id'] ?>">
                                <p class="font-semibold text-xl md:text-lg"><?= $request['name'] . ' ' . $request['lastname'] ?></p>
                            </a>
                            <span 
                                class="block text-sm text-neutral-400 truncate overflow-hidden whitespace-nowrap w-full"
                                title="<?= htmlspecialchars($request['description']) ?>">
                                <?= $request['description'] ?: 'Sin descripción' ?>
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <form method="POST" action="/follow/respond">
                            <input type="hidden" name="follower_id" value="<?= $request['id'] ?>">
                            <input type="hidden" name="status" value="1">
                            <button type="submit" class="border focus:ring-4 focus:outline-none rounded-lg px-3 py-2 text-sm font-medium text-center border-green-500 text-green-500 hover:text-white hover:bg-green-600 focus:ring-green-800 cursor-pointer">
                                Aceptar
                            </button>
                        </form>
                        <form method="POST" action="/follow/respond">
                            <input type="hidden" name="follower_id" value="<?= $request['id'] ?>">
                            <input type="hidden" name="status" value="0">
                            <button type="submit" class="border focus:ring-4 focus:outline-none rounded-lg px-3 py-2 text-sm font-medium text-center border-red-500 text-red-500 hover:text-white hover:bg-red-600 focus:ring-red-900 cursor-pointer">
                                Rechazar
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>






