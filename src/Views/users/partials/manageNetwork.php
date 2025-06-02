<div class="w-full bg-neutral-900 rounded-lg border border-neutral-700 divide-y-1 divide-neutral-700 overflow-hidden animate__animated animate__fadeIn animate__faster">
    <div class="p-4">
        <h3 class="text-white text-xl font-semibold">Gestionar mi red</h3>
    </div>
    <ul class="">
        <li>
            <a href="/user/profile" class="flex gap-2 p-4 items-center text-lg hover:bg-neutral-600 text-neutral-200 hover:text-white">
                <i data-lucide="users-round"></i> 
                Mi Red
            </a>
        </li>
        <li>
            <a href="/auth/logout" class="flex gap-2 justify-between p-4 items-center text-lg hover:bg-neutral-600 text-neutral-200 hover:text-white">
                <div class="flex gap-2">
                    <i data-lucide="user-round-check"></i> 
                    Siguiendo
                </div>
                <span>
                    <?= $user['follows_count'] ?>
                </span>
            </a>
        </li>
        <li>
            <a href="/auth/register" class="flex gap-2 justify-between p-4 items-center text-lg hover:bg-neutral-600 text-neutral-200 hover:text-white">
                <div class="flex gap-2">
                    <i data-lucide="user"></i>
                    Seguidores
                </div>
                <span>
                    <?= $user['followers_count'] ?>
                </span>
            </a>
        </li>
        <li>
            <a href="/auth/login" class="flex gap-2 p-4 items-center text-lg hover:bg-neutral-600 text-neutral-200 hover:text-white">
                <i data-lucide="users"></i> 
                Grupos
            </a>
        </li>
    </ul>
</div>