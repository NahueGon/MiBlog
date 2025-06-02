<?php

namespace App\Controllers;

use App\Core\{ Auth, Controller };

use App\Models\{ User, Post, Like, Notification };

class LikeController extends Controller
{
    private $postModel;
    private $likeModel;
    private $notificationModel;

    public function __construct()
    {
        $this->postModel = new Post();
        $this->likeModel = new Like();
        $this->notificationModel = new Notification();
    }

    public function toggleLike($postId)
    {
        $user = Auth::user();

        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $parsedUrl = parse_url($referer, PHP_URL_PATH);

        $post = $this->postModel->getById($postId);

        if ($this->likeModel->userAlreadyLiked($postId, $user['id'])) {
            $this->likeModel->removeLike($postId, $user['id']);
            $this->postModel->decrementLikesCount($postId);
        } else {
            $this->likeModel->addLike($postId, $user['id']);
            $this->postModel->incrementLikesCount($postId);

            if ($post && $post['user_id'] !== $user['id']) {
                $this->notificationModel->create($post['user_id'], 'like', [
                    'from_user_id' => $user['id'],
                    'post_id'      => $postId
                ]);
            }
        }

        header('Location: /post/show/'. $postId);
        exit;
    }
}