
<div class="flex flex-col gap-4 bg-neutral-900 p-4 rounded-lg border border-neutral-700 animate__animated animate__fadeIn animate__faster">
    <ul class="flex flex-wrap text-sm font-medium text-center text-neutral-400">
        <li class="me-2">
            <a href="#" class="inline-block px-4 py-3 text-white bg-blue-600 rounded-lg active" aria-current="page">Todos</a>
        </li>
        <li class="me-2">
            <a href="#"  class="inline-block px-4 py-3 rounded-lg hover:bg-neutral-800 hover:text-white">No Leidos</a>
        </li>
        <li class="me-2">
            <a href="#" class="inline-block px-4 py-3 rounded-lg hover:bg-neutral-800 hover:text-white">Leidos</a>
        </li>
    </ul>
</div>
<ul class="text-sm font-medium border rounded-lg bg-neutral-900 border-neutral-600 text-white overflow-hidden animate__animated animate__fadeIn animate__faster">
    <?php if (empty($notifications)): ?>
        <div class="text-center text-neutral-400 p-4">No tienes notificaciones por el momento.</div>
    <?php else: ?>
        <?php foreach ($notifications as $notification): ?>
            <?php
                $data = json_decode($notification['data'], true);

                $url = isset($data['post_id'])
                    ? '/post/show/' . htmlspecialchars($data['post_id'])
                    : '/user/profile/' . htmlspecialchars($data['from_user_id']);
            ?>
            <li>
                <a href="<?= $url ?>" >
                    <div class="w-full p-4 flex gap-4 justify-between items-center border-b border-neutral-400 <?= $notification['is_read'] ? 'bg-white' : 'bg-blue-100 hover:bg-blue-200' ?>">
                        <div class="flex gap-4 items-center">
                            <img class="size-18 min-w-18 rounded-full" src="<?= $notification['from_profile_image'] ? '/uploads/users/user_' . $notification['from_id'] . '/' . $notification['from_profile_image'] : '/uploads/users/anon-profile.jpg' ?>" alt="Rounded avatar">
                            <div class="flex flex-col gap-4 text-sm text-neutral-800">
                                <?php switch ($notification['type']):
                                    case 'like': ?>
                                        A <?= $notification['from_username'] ?? 'Alguien' ?> le gustó tu publicación.
                                        <?php break; ?>
        
                                    <?php case 'comment': ?>
                                        <p><?= $notification['from_username'] ?? 'Alguien' ?> comentó en tu publicación.</p>
                                        
                                        <div class="bg-transparent p-4 rounded-lg border border-neutral-400">
                                            <?= $data ? $data['content'] : '' ?>
                                        </div>
                                        <?php break; ?>
        
                                    <?php case 'follow_accept': ?>
                                        <?= $notification['from_username'] ?? 'Alguien' ?> aceptó tu solicitud de amistad.
                                        
                                        <?php break; ?>
        
                                    <?php case 'follow_rejected': ?>
                                        <?= $notification['from_username'] ?? 'Alguien' ?> aceptó tu solicitud de amistad.
                                        <?php break; ?>
        
                                    <?php default: ?>
                                        🔔 Tienes una nueva notificación.
                                <?php endswitch; ?>
                            </div>
                        </div>
                        <div class="text-xs text-neutral-500 w-full text-end max-w-15"><?= $notification['time_ago'] ?></div>
                    </div>
                </a>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>