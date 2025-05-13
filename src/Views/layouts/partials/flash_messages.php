<?php if (!empty($notifications)): ?>
    <script>
        <?php foreach ($notifications as $type => $messages): ?>
            <?php foreach ($messages as $message): ?>
                const msg = <?= json_encode($message) ?>;
                Toast.fire({
                    icon: '<?= $type ?>',
                    title: msg.text ?? msg
                });

                if (msg.redirect) {
                    setTimeout(() => {
                        window.location.href = msg.redirect;
                    }, 2200);
                }
            <?php endforeach; ?>
        <?php endforeach; ?>
    </script>
<?php endif; ?>