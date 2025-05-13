<div id="static-modal" data-modal-backdrop="static" tabindex="-1" class="<?= $email || $password ? '' : 'hidden' ?> overflow-y-auto overflow-x-hidden fixed top-0 bottom-0 right-0 left-0 z-50 justify-center items-center w-full">
    <div class="absolute inset-0 bg-neutral-900 opacity-50"></div>
    <div class="relative p-4 w-full h-full flex justify-center items-center animate__animated animate__fadeIn animate__faster">
        <div class="relative rounded-lg shadow-sm bg-neutral-800 z-50">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-neutral-700">
                <h3 class="text-xl font-semibold text-white">
                    Editar Perfil
                </h3>
                <button type="button" class="text-neutral-400 bg-transparent rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center hover:bg-neutral-700 hover:text-white cursor-pointer" data-modal-hide="static-modal">
                    <i data-lucide="x" class="size-8"></i>
                    <span class="sr-only">Cerrar modal</span>
                </button>
            </div>
            <div class="p-6 md:p-5 space-y-4 flex flex-col sm:flex-row gap-5 justify-center items-center min-w-100">
                <form action="/user/update" method="POST" id="formUpdate" class="w-full" enctype="multipart/form-data">
                    <div onClick="openImageInput('image')"  id="imageFields" class="relative hidden max-h-45 max-w-45 m-auto overflow-hidden rounded-lg cursor-pointer group">
                        <div class="absolute w-full bg-neutral-900 text-center bottom-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            Cambiar
                        </div>
                        <?php if($user['profile_image']): ?>
                            <img class="w-full h-full" src="<?= '/uploads/users/' . 'user_' . $user['id'] . '/' .  $user['profile_image'] ?>" alt="user photo">
                        <?php else: ?>
                            <img class="w-full h-full" src="/uploads/users/anon-profile.jpg" alt="user photo">
                        <?php endif; ?>
                        
                        <input id="imageInput" type="file" name="imageInput" class="hidden" accept="image/*">
                    </div>
                    <div onClick="openImageInput('background')"  id="backgroundImageFields" class="relative hidden m-auto overflow-hidden rounded-lg cursor-pointer group">
                        <div class="absolute w-full bg-neutral-900 text-center bottom-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            Cambiar
                        </div>
                        <div class="w-full min-w-150 min-h-40 bg-cover bg-center bg-no-repeat <?= $user['profile_background'] ? '' : 'bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl'?>" 
                            style="<?= $user['profile_background'] 
                                ? 'background-image: url(/uploads/users/user_' . $user['id'] . '/' . $user['profile_background'] . ')'
                                : '' ?>">
                        </div>
                        <input id="backgroundImageInput" type="file" name="backgroundImageInput" class="hidden" accept="image/*">
                    </div>
                    <div id="profileFields" class="<?= ($name || $lastname || $email || $old_password || $new_password || $credentials) ? '' : 'hidden' ?>">
                        <div class="flex gap-3 flex-col sm:flex-row mb-2 w-90">
                            <div class="relative mb-2 w-full">
                                <input type="text" name="name" id="name" class="block px-2.5 pb-2.5 pt-4 w-full text-sm bg-transparent rounded-lg border-1 appearance-none text-white border-neutral-500 focus:border-blue-500 focus:outline-none focus:ring-0 peer <?= $name ? 'text-red-500 border-red-500' : 'text-neutral-400' ?>" placeholder=" " value="<?= $user['name'] ?? '' ?>"/>
                                <label for="name" class="absolute text-sm text-neutral-300 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-neutral-800 px-2 peer-focus:px-2 peer-focus:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 <?= $name ? 'text-red-500' : 'text-neutral-400' ?>">Nombre</label>
                                <?php if (!empty($name)): ?>
                                    <p class="text-xs text-red-500 mt-1">
                                        <?= htmlspecialchars($name[0]) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="relative mb-2 w-full">
                                <input type="text" name="lastname" id="lastname" class="block px-2.5 pb-2.5 pt-4 w-full text-sm bg-transparent rounded-lg border-1 appearance-none text-white border-neutral-500 focus:border-blue-500 focus:outline-none focus:ring-0 peer <?= $lastname ? 'text-red-500 border-red-500' : 'text-neutral-400' ?>" placeholder=" " value="<?= $user['lastname'] ?? '' ?>"/>
                                <label for="lastname" class="absolute text-sm text-neutral-300 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-neutral-800 px-2 peer-focus:px-2 peer-focus:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 <?= $lastname ? 'text-red-500' : 'text-neutral-400' ?>">Apellido</label>
                                <?php if (!empty($lastname)): ?>
                                    <p class="text-xs text-red-500 mt-1">
                                        <?= htmlspecialchars($lastname[0]) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="relative mb-4 w-full">
                            <input type="email" name="email" id="email" autocomplete="username" class="block px-2.5 pb-2.5 pt-4 w-full text-sm bg-transparent rounded-lg border-1 appearance-none text-white focus:border-blue-500 focus:outline-none focus:ring-0 peer <?= $email ? 'text-red-500 border-red-500' : 'text-neutral-400' ?>" placeholder=" " value="<?= $user['email'] ?? '' ?>"/>
                            <label for="email" class="absolute text-sm duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-neutral-800 px-2 peer-focus:px-2 peer-focus:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 <?= $email ? 'text-red-500' : 'text-neutral-400' ?>">Email</label>
                            <?php if (!empty($email)): ?>
                                <p class="text-xs text-red-500 mt-1">
                                    <?= htmlspecialchars($email[0]) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="flex gap-3 flex-col sm:flex-row w-90">
                            <div class="relative mb-1 w-full">
                                <input oninput="toggleVisibilityButton(this)" type="password" name="old_password" id="old_password" autocomplete="old_password" class="block px-2.5 pb-2.5 pt-4 w-full text-sm bg-transparent rounded-lg border-1 appearance-none text-white border-neutral-500 focus:border-blue-500 focus:outline-none focus:ring-0 peer <?= $old_password ? 'text-red-500 border-red-500' : 'text-neutral-400' ?>" placeholder=" "/>
                                <label for="old_password" class="absolute text-sm text-neutral-300 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-neutral-800 px-2 peer-focus:px-2 peer-focus:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 <?= $old_password ? 'text-red-500' : 'text-neutral-400' ?>">Contraseña Actual</label>
                                <button type="button" onclick="togglePassword('old_password', this)" id="toggle-old_password" class="absolute end-2 top-3 cursor-pointer text-neutral-300 hidden">
                                    <i data-lucide="eye"></i>
                                </button>
                                <?php if (!empty($old_password)): ?>
                                    <p class="text-xs text-red-500 mt-1">
                                        <?= htmlspecialchars($old_password[0]) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="relative w-full">
                                <input oninput="toggleVisibilityButton(this)" type="password" name="new_password" id="new_password" autocomplete="new_password" class="block px-2.5 pb-2.5 pt-4 w-full text-sm bg-transparent rounded-lg border-1 appearance-none text-white focus:border-blue-500 focus:outline-none focus:ring-0 peer <?= $new_password ? 'text-red-500 border-red-500' : 'text-neutral-400' ?>" placeholder=" "/>
                                <label for="new_password" class="absolute text-sm text-neutral-300 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-neutral-800 px-2 peer-focus:px-2 peer-focus:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 <?= $new_password ? 'text-red-500' : 'text-neutral-400' ?>">Contraseña Nueva</label>
                                <button type="button" onclick="togglePassword('new_password', this)" id="toggle-new_password" class="absolute end-2 top-3 cursor-pointer text-neutral-300 hidden">
                                    <i data-lucide="eye"></i>
                                </button>
                                <?php if (!empty($new_password)): ?>
                                    <p class="text-xs text-red-500 mt-1">
                                        <?= htmlspecialchars($new_password[0]) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if (!empty($credentials)): ?>
                            <p class="text-xs text-red-500 mt-1">
                                <?= htmlspecialchars($credentials[0]) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div id="descriptionFields" class="relative w-full hidden">
                        <textarea name="description" id="description" rows="3" class="block px-2.5 pb-2.5 pt-4 w-full text-sm bg-transparent rounded-lg border-1 appearance-none text-white focus:border-blue-500 focus:outline-none focus:ring-0 peer" placeholder=""><?= $user['description'] ?? '' ?></textarea>
                        <label for="description" class="absolute text-sm text-neutral-300 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-neutral-800 px-2 peer-focus:px-2 peer-focus:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Descripcion</label>
                    </div>

                    <div id="locationFields" class="hidden">
                         <div class="relative mb-2 w-full">
                            <label for="id_country" class="absolute text-sm text-neutral-300 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-neutral-800 px-2 peer-focus:px-2 peer-focus:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">País</label>
                            <select name="id_country" id="id_country" class="border text-sm rounded-lg block w-full p-2.5 bg-neutral-800 border-neutral-700 placeholder-neutral-500 text-white focus:ring-blue-500 focus:border-blue-500 mb-4"">
                                <option value="<?= $user['country']['id'] ?>">Seleccionar País</option>
                                <?php foreach ($countries as $country): ?>
                                    <option value="<?= $country['id'] ?>" <?= $country['id'] == ($user['country']['id'] ?? null) ? 'selected' : '' ?>>
                                        <?= $country['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                       <div class="relative mb-2 w-full">
                            <label for="id_province" class="absolute text-sm text-neutral-300 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-neutral-800 px-2 peer-focus:px-2 peer-focus:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Provincia</label>
                            <select name="id_province" id="id_province" class="border text-sm rounded-lg block w-full p-2.5 bg-neutral-800 border-neutral-700 placeholder-neutral-500 text-white focus:ring-blue-500 focus:border-blue-500 mb-4">
                                <option value="<?= $user['province']['id'] ?>">Seleccionar Provincia</option>
                                <?php foreach ($provinces as $province): ?>
                                    <?php if ($province['country_id'] == ($user['country']['id'] ?? null)): ?>
                                        <option value="<?= $province['id'] ?>" <?= $province['id'] == ($user['province']['id'] ?? null) ? 'selected' : '' ?>>
                                            <?= $province['name'] ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex gap-2 items-center justify-end p-4 md:p-5 border-t rounded-b border-neutral-600">
                <button form="formUpdate" type="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800 cursor-pointer">Editar</button>
            </div>
        </div>
    </div>
</div>

<script>
    window.provinces = <?= json_encode($provinces) ?>;
</script>