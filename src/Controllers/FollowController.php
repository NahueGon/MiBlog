<?php

namespace App\Controllers;

use App\Core\{ Auth, Controller, Validator, ValidationMessage, Notifier };

use App\Models\{ User, Follow, Notification };

use App\Traits\{ TimeAgoTrait, FlashMessageTrait };

use App\Services\{ SessionUpdaterService };

class FollowController extends Controller
{
    private $followModel;
    private $notificationModel;
    private $sessionUpdater;

    public function __construct()
    {
        $this->followModel = new Follow();
        $this->notificationModel = new Notification();
        $this->sessionUpdater = new SessionUpdaterService();
    }

    public function follow(int $userId)
    {
        if (!Auth::check()) {
            header('Location: /');
            exit;
        }

        $currentUser = Auth::user();

        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $parsedUrl = parse_url($referer, PHP_URL_PATH);

        if ($currentUser['id'] === $userId) {
            header('Location: /');
            exit;
        }

        if (!$this->followModel->followUser($currentUser['id'], $userId)) {
            Notifier::add('error', 'Error al enviar solicitud');
            header('Location: ' . $parsedUrl);
            exit;
        }

        Notifier::add('success', 'Solicitud enviada exitosamente');
        header('Location: ' . $parsedUrl);
        exit;
    }

    public function unfollow(int $userId)
    {
        if (!Auth::check()) {
            header('Location: /');
            exit;
        }

        $currentUser = Auth::user();

        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $parsedUrl = parse_url($referer, PHP_URL_PATH);

        if ($currentUser['id'] === $userId) {
            header('Location: /');
            exit;
        }

        if (!$this->followModel->unfollowUser($currentUser['id'], $userId)) {
            Notifier::add('error', 'Error al dejar de seguir');
            header('Location: ' . $parsedUrl);
            exit;
        }

        $this->sessionUpdater->refresh($currentUser['id']);

        Notification::add('success', 'Dejaste de seguir a este usuario');
        header('Location: ' . $parsedUrl);
        exit;
    }

    public function cancel(int $userId)
    {
        if (!Auth::check()) {
            header('Location: /');
            exit;
        }

        $currentUser = Auth::user();

        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $parsedUrl = parse_url($referer, PHP_URL_PATH);

        if (!$this->followModel->cancelRequest($currentUser['id'], $userId)) {
            Notifier::add('error', 'No se pudo cancelar la solicitud');
            header('Location: ' . $parsedUrl);
            exit;
        }

        Notifier::add('success', 'Solicitud cancelada exitosamente');
        header('Location: ' . $parsedUrl);
        exit;
    }

    public function respond()
    {
        if (!Auth::check()) {
            header('Location: /');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $followerId = $_POST['follower_id'] ?? null;
            $statusCode = $_POST['status'] ?? null;

            if (!$followerId || !in_array($statusCode, ['0', '1'])) {
                Notifier::add('error', 'Datos inválidos');
                header('Location: /user/network');
                exit;
            }

            $currentUser = Auth::user();

            $referer = $_SERVER['HTTP_REFERER'] ?? '/';
            $parsedUrl = parse_url($referer, PHP_URL_PATH);

            $status = $statusCode === 1 ? 'accepted' : 'rejected';

            if ($statusCode === '0') {
                $this->followModel->deleteRequest((int)$followerId, $currentUser['id']);

                $this->notificationModel->create((int)$followerId, 'follow_rejected', [
                    'from_user_id' => $currentUser['id']
                ]);

                Notifier::add('info', 'Solicitud rechazada');
                header('Location: ' . $parsedUrl);
                exit;
            }

            if (!$this->followModel->respondRequest((int)$followerId, $currentUser['id'], 'accepted')) {
                Notifier::add('error', 'No se pudo aceptar la solicitud');

                header('Location: ' . $parsedUrl);
                exit;
            }

            $this->notificationModel->create((int)$followerId, 'follow_accept', [
                'from_user_id' => $currentUser['id']
            ]);

            $this->sessionUpdater->refresh($currentUser['id']);
            
            Notifier::add('success', 'Solicitud aceptada');
            header('Location: ' . $parsedUrl);
            exit;
        }
    }

}