<?php if (!empty($notifications)): ?>
    <script>
        <?php foreach ($notifications as $type => $messages): ?>
            <?php foreach ($messages as $message): ?>
                const msg = <?= json_encode($message) ?>;
                Toast.fire({
                    icon: '<?= $type ?>',
                    title: msg.text ?? msg
                });

                if (msg.path) {
                    if (msg.time === true) {
                        setTimeout(() => {
                            window.location.href = msg.path;
                        }, 2200);
                    } else {
                        window.location.href = msg.path;
                    }
                }
            <?php endforeach; ?>
        <?php endforeach; ?>
    </script>
<?php endif; ?>