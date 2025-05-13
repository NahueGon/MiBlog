<form action="/auth/register" method="POST" class="w-full m-auto max-w-sm p-4 animate__animated animate__fadeIn animate__faster">
    <div class="flex gap-2 items-center mb-7">
        <i data-lucide="file-text" class="size-8"></i> 
        <h2 class="text-3xl font-semibold">Regístrate</h2>
    </div>
    <div class="relative mb-2 w-full">
        <input type="text" name="name" id="name" class="block px-2.5 pb-2.5 pt-4 w-full text-sm bg-transparent rounded-lg border-1 appearance-none text-white border-neutral-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer <?= $name ? 'text-red-500 border-red-500' : 'text-neutral-400' ?>" placeholder=" " value="<?= $_POST['name'] ?? '' ?>"/>
        <label for="name" class="absolute text-sm text-neutral-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-neutral-900 px-2 peer-focus:px-2 peer-focus:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 <?= $name ? 'text-red-500' : 'text-neutral-400' ?>">Nombre</label>
        <?php if (!empty($name)): ?>
            <p class="text-xs text-red-500 mt-1">
                <?= htmlspecialchars($name[0]) ?>
            </p>
        <?php endif; ?>
    </div>
    <div class="relative mb-4 w-full">
        <input type="text" name="lastname" id="lastname" class="block px-2.5 pb-2.5 pt-4 w-full text-sm bg-transparent rounded-lg border-1 appearance-none text-white border-neutral-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer <?= $lastname ? 'text-red-500 border-red-500' : 'text-neutral-400' ?>" placeholder=" " value="<?= $_POST['lastname'] ?? '' ?>"/>
        <label for="lastname" class="absolute text-sm text-neutral-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-neutral-900 px-2 peer-focus:px-2 peer-focus:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 <?= $lastname ? 'text-red-500' : 'text-neutral-400' ?>">Apellido</label>
        <?php if (!empty($lastname)): ?>
            <p class="text-xs text-red-500 mt-1">
                <?= htmlspecialchars($lastname[0]) ?>
            </p>
        <?php endif; ?>
    </div>
    <div class="relative mb-2 w-full">
        <input type="email" name="email" id="email" autocomplete="username" class="block px-2.5 pb-2.5 pt-4 w-full text-sm bg-transparent rounded-lg border-1 appearance-none text-white border-neutral-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer <?= $email ? 'text-red-500 border-red-500' : 'text-neutral-400' ?>" placeholder=" " value="<?= $_POST['email'] ?? '' ?>"/>
        <label for="email" class="absolute text-sm duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-neutral-900 px-2 peer-focus:px-2 peer-focus:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 <?= $email ? 'text-red-500' : 'text-neutral-400' ?>">Email</label>
        <?php if (!empty($email)): ?>
            <p class="text-xs text-red-500 mt-1">
                <?= htmlspecialchars($email[0]) ?>
            </p>
        <?php endif; ?>
    </div>
    <div class="flex gap-2 flex-col sm:flex-row mb-2">
        <div class="relative grow-3">
            <input oninput="toggleVisibilityButton(this)" type="password" name="password" id="password" autocomplete="new-password" class="block px-2.5 pb-2.5 pt-4 w-full text-sm bg-transparent rounded-lg border-1 appearance-none text-white border-neutral-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer <?= $password ? 'text-red-500 border-red-500' : 'text-neutral-400' ?>" placeholder=" "/>
            <label for="password" class="absolute text-sm text-neutral-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-neutral-900 px-2 peer-focus:px-2 peer-focus:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 <?= $password ? 'text-red-500' : 'text-neutral-400' ?>">Contraseña</label>
            <button type="button" onclick="togglePassword('password', this)" id="toggle-password" class="absolute end-2 top-3 cursor-pointer text-neutral-400 hidden">
                <i data-lucide="eye"></i>
            </button>
            <?php if (!empty($password)): ?>
            <p class="text-xs text-red-500 mt-1">
                <?= htmlspecialchars($password[0]) ?>
            </p>
        <?php endif; ?>
        </div>
        <div class="relative grow-3">
            <input oninput="toggleVisibilityButton(this)" type="password" name="repeat_password" id="repeat_password" autocomplete="new-password" class="block px-2.5 pb-2.5 pt-4 w-full text-sm bg-transparent rounded-lg border-1 appearance-none text-white border-neutral-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer <?= $repeat_password ? 'text-red-500 border-red-500' : 'text-neutral-400' ?>" placeholder=" "/>
            <label for="repeat_password" class="absolute text-sm text-neutral-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-neutral-900 px-2 peer-focus:px-2 peer-focus:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 <?= $repeat_password ? 'text-red-500' : 'text-neutral-400' ?>">Repetir Contraseña</label>
            <button type="button" onclick="togglePassword('repeat_password', this)" id="toggle-repeat_password" class="absolute end-2 top-3 cursor-pointer text-neutral-400 hidden">
                <i data-lucide="eye"></i>
            </button>
            <?php if (!empty($repeat_password)): ?>
                <p class="text-xs text-red-500 mt-1">
                    <?= htmlspecialchars($repeat_password[0]) ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <div class="w-full flex mb-5">
        <button type="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-base w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800 ml-auto cursor-pointer">Enviar</button>
    </div>
    <p class="text-sm text-center text-neutral-500">
        Ya tienes cuenta?
        <a href="/auth/login" class="font-bold hover:text-blue-600">Inicia Sesión</a>
    </p>
</form>