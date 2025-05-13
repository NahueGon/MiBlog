<!DOCTYPE html>
<html lang="es" class="dark">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $title ?? 'MiBlog' ?></title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
        <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
        <link href="/css/styles.css" rel="stylesheet">

        <script src="https://unpkg.com/lucide@latest"></script>
    </head>
    <body class="bg-neutral-950 text-white h-screen">
        <?php 
            require_once __DIR__ . '/partials/header.php'; 
        ?>
        
        <main class="mb-20">
            <?= $content ?? '' ?>
        </main>

        <?php 
            require_once __DIR__ . '/partials/footer.php'; 
        ?>

        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="/js/components/lucideIcon/passwordIcons.js"></script>
        <script src="/js/components/sweetAlert/flash_messages.js"></script>
        <script src="/js/components/updateModals/selectModal.js"></script>
        <script src="/js/components/loadProvinces/loadProvinces.js"></script>

        <?php include __DIR__ . '/partials/flash_messages.php'; ?>
    </body>
</html>