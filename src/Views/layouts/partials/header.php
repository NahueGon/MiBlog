<?php

    use App\Core\Auth;

    $user = Auth::user();
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

?>

<nav class="fixed w-full bg-neutral-900 z-100">
    <div class="w-full sm:max-w-xl md:max-w-4xl lg:max-w-7xl flex flex-wrap items-center justify-between mx-auto p-4 animate__animated animate__fadeIn animate__faster">
        <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse order-2 md:order-1">
            <span class="self-center text-2xl font-semibold whitespace-nowrap">MiBlog</span>
        </a>
        <div class="flex gap-4 items-center order-3 md:order-3 space-x-3 md:space-x-0 rtl:space-x-reverse">
            <button type="button" class="flex text-sm bg-neutral-900 rounded-full me-0 focus:ring-4 focus:ring-neutral-800 cursor-pointer" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                <span class="sr-only">Open user menu</span>
                <?php if($user): ?>
                    <?php if($user['profile_image']): ?>
                        <img class="size-10 rounded-full" src="<?= '/uploads/users/' . 'user_' . $user['id'] . '/' .  $user['profile_image'] ?>" alt="user photo">
                    <?php else: ?>
                        <img class="size-10 rounded-full" src="/uploads/users/anon-profile.jpg" alt="user photo">
                    <?php endif; ?>
                <?php else: ?>
                    <img class="size-10 rounded-full" src="/uploads/users/anon-profile.jpg" alt="user photo">
                <?php endif; ?>
            </button>
            <!-- Dropdown menu -->
            <div class="z-50 hidden my-4 text-base list-none divide-y rounded-lg shadow-sm bg-neutral-800 divide-neutral-700" id="user-dropdown">
                <div class="px-4 py-3">
                    <span class="block text-sm text-white"> 
                        <?= 
                            $user
                            ? $user['name'] . ' ' . $user['lastname']
                            : 'Contéctate'
                        ?>
                    </span>
                </div>
                <ul class="py-2" aria-labelledby="user-menu-button">
                    <?php if($user): ?>
                        <li>
                            <a href="/user/profile" class="flex gap-2 items-center px-4 py-2 text-sm hover:bg-neutral-600 text-neutral-200 hover:text-white">
                                <i data-lucide="user"></i> 
                                Mi Perfil
                            </a>
                        </li>
                        <li>
                            <a href="/auth/logout" class="flex gap-2 items-center px-4 py-2 text-sm hover:bg-neutral-600 text-neutral-200 hover:text-white">
                                <i data-lucide="log-out"></i> 
                                Cerrar Sesión
                            </a>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="/auth/register" class="flex gap-2 items-center px-4 py-2 text-sm hover:bg-neutral-600 text-neutral-200 hover:text-white">
                                <i data-lucide="file-text"></i>
                                Regístro
                            </a>
                        </li>
                        <li>
                            <a href="/auth/login" class="flex gap-2 items-center px-4 py-2 text-sm hover:bg-neutral-600 text-neutral-200 hover:text-white">
                                <i data-lucide="log-in"></i> 
                                Iniciar Sesión
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <button data-collapse-toggle="navbar-user" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm rounded-lg md:hidden focus:outline-none focus:ring-2 text-neutral-400 hover:bg-neutral-700 focus:ring-neutral-600" aria-controls="navbar-user" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <i data-lucide="align-justify"></i> 
        </button>
        <div class="items-center justify-between hidden w-full order-3 md:flex md:w-auto md:order-2" id="navbar-user">
            <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-neutral-700 rounded-lg md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 bg-neutral-950 md:bg-neutral-900">
                <li>
                    <a href="/" class="py-2 px-3 rounded-sm md:p-0 <?= $currentPath === '/' ? 'text-blue-500 font-semibold' : 'md:hover:text-blue-600 font-semibold' ?> hover:bg-neutral-700 md:hover:bg-transparent border-neutral-700 flex gap-2 items-center">
                        <i data-lucide="house"></i> 
                        Home
                    </a>
                </li>
                <li>
                    <a href="/user/network" class="py-2 px-3 rounded-sm md:p-0 <?= $currentPath === '/user/network' ? 'text-blue-500 font-semibold' : 'md:hover:text-blue-600 font-semibold' ?> hover:bg-neutral-700 md:hover:bg-transparent border-neutral-700 flex gap-2 items-center">
                        <i data-lucide="users"></i> 
                        Red
                    </a>
                </li>
                <li>
                    <a href="/user/notifications" class="py-2 px-3 rounded-sm md:p-0 <?= $currentPath === '/user/notifications' ? 'text-blue-500 font-semibold' : 'md:hover:text-blue-600 font-semibold' ?> hover:bg-neutral-700 md:hover:bg-transparent border-neutral-700 flex gap-2 items-center">
                        <i data-lucide="bell"></i> 
                        Notificaciones
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>