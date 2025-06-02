<?php if (!$posts): ?>
    <div class="bg-neutral-900 w-full border-1 border-neutral-700 rounded-lg p-4 flex flex-col gap-4">
        <p class="text-white text-base mb-2">No hay Posts para mostrar</p>
    </div>
    
<?php endif; ?>

<?php foreach ($posts as $post): ?>
    <?php 
        include  __DIR__ . '/post.php'; 
    ?>
<?php endforeach; ?>