<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $title ?? 'MiBlog' ?></title>
        
        <link href="/css/styles.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
        
        <script src="https://unpkg.com/lucide@latest"></script>
    </head>
    <body class="bg-neutral-900 text-white h-screen">
        <main class="flex flex-col justify-center items-center h-full">
            <?= $content ?? '' ?>
            
        </main>
        
        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="/js/components/lucideIcon/passwordIcons.js"></script>
        <script src="/js/components/sweetAlert/flash_messages.js"></script>

        <?php include __DIR__ . '/partials/flash_messages.php'; ?>
    </body>
</html>