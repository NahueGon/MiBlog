<?php


namespace App\Controllers;

use App\Core\{ Auth, Controller, Validator, ValidationMessage, Notifier };

use App\Models\{ User, Country, Province, Post, Like, Comment, ProfileView, Follow, Notification };

use App\Traits\{ TimeAgoTrait, SanitizerTrait, FlashMessageTrait };

class NotificationController extends Controller
{
    private Notification $notification;

    public function __construct()
    {
        $this->notification = new Notification();
    }

    public function send(int $userId, string $type, array $data): void
    {
        $this->notification->create($userId, $type, $data);
    }

    public function markAsRead(int $id): void
    {
        $this->notification->markAsRead($id);
    }

    public function countUnread(int $userId): int
    {
        return $this->notification->countUnread($userId);
    }
}
