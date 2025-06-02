<?php

namespace App\Services;

use App\Models\{ Post, Follow, ProfileView };

class SessionUpdaterService
{
    private $postModel;
    private $followModel;
    private $profileViewModel;

    public function __construct()
    {
        $this->postModel = new Post();
        $this->followModel = new Follow();
        $this->profileViewModel = new ProfileView();
    }

    public function refresh(int $userId): void
    {
        $_SESSION['user_post_count'] = $this->postModel->countByUserId($userId);
        $_SESSION['user_views_count'] = $this->profileViewModel->countByUserId($userId);
        $_SESSION['user_followers_count'] = $this->followModel->countFollowers($userId);
        $_SESSION['user_follows_count'] = $this->followModel->countFollowing($userId);
    }
}
