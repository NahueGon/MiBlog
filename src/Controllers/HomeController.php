<?php

namespace App\Controllers;

use App\Core\{ Auth, Controller, Validator, ValidationMessage, Notification };

use App\Models\{ User, Post, Like, Comment, Country, Province };

use App\Traits\{ TimeAgoTrait, FlashMessageTrait };

class HomeController extends Controller
{
    use TimeAgoTrait, FlashMessageTrait; 

    public function index()
    {
        $user = Auth::user();
        $userModel = new User();
        $countryModel = new Country();
        $provinceModel = new Province();
        $postModel = new Post();
        $likeModel = new Like();
        $commentModel = new Comment();

        $users = $this->getUserSuggestions($userModel, $user['id'] ?? null);
        $posts = $this->enrichPosts($postModel->allWithAuthors(), $user);

        $provinceId = $user['id_province'] ?? null;
        $province = $country = '';

        if ($provinceId !== null && is_int($provinceId)) {
            $province = $provinceModel->getById($provinceId);
            $country = $countryModel->getById($province['country_id']);
        }

        $validationMessages = $this->getValidationMessages();
        $notificationMessages = $this->getNotificationMessages();

        $this->view('home', array_merge([
            'title' => 'MiBlog',
            'user' => $user,
            'users' => $users,
            'posts' => $posts,
            'country' => $country,
            'province' => $province,
            'notifications' => $notificationMessages
        ], $validationMessages));

        ValidationMessage::clear();
        Notification::clear();
    }

    private function getUserSuggestions(User $userModel, ?int $excludeId): array
    {
        $allUsers = $userModel->all($excludeId);
        shuffle($allUsers);
        return array_slice($allUsers, 0, 3);
    }

    private function enrichPosts(array $posts, ?array $user): array
    {
        $likeModel = new Like();
        $commentModel = new Comment();

        foreach ($posts as &$post) {
            $post['liked_by_user'] = $user 
                ? $likeModel->userAlreadyLiked($post['id'], $user['id'])
                : false;
            $post['comments'] = $commentModel->getByPostId($post['id']);
            $post['time_ago'] = $this->getTimeAgo($post['created_at']);

            foreach ($post['comments'] as &$comment) {
                $comment['time_ago'] = $this->getTimeAgo($comment['created_at']);
            }

        }

        return $posts;
    }

}
