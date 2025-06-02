<?php

namespace App\Controllers;

use App\Core\{ Auth, Controller, Validator, ValidationMessage, Notifier };

use App\Models\{ User, Post, Like, Comment, Country, Province, Follow };

use App\Traits\{ TimeAgoTrait, FlashMessageTrait };

class HomeController extends Controller
{
    use TimeAgoTrait, FlashMessageTrait; 

    private $userModel;
    private $postModel;
    private $countryModel;
    private $provinceModel;
    private $likeModel;
    private $commentModel;
    private $followModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->postModel = new Post();
        $this->countryModel = new Country();
        $this->provinceModel = new Province();
        $this->likeModel = new Like();
        $this->commentModel = new Comment();
        $this->followModel = new Follow();
    }

    public function index()
    {
        $user = Auth::user();

        $parsedUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $users = $this->getUserSuggestions($this->userModel, $user['id'] ?? null);
        $posts = $this->enrichPosts($this->postModel->all(), $user);

        $provinceId = $user['id_province'] ?? null;
        $province = $country = '';

        if ($provinceId !== null && is_int($provinceId)) {
            $province = $this->provinceModel->getById($provinceId);
            $country = $this->countryModel->getById($province['country_id']);
        }

        $validationMessages = $this->getValidationMessages();
        $notificationMessages = $this->getNotificationMessages();

        $this->view('home', array_merge([
            'title' => 'MiBlog',
            'user' => $user,
            'url' => $parsedUrl,
            'users' => $users,
            'posts' => $posts,
            'country' => $country,
            'province' => $province,
            'notifications' => $notificationMessages
        ], $validationMessages));

        ValidationMessage::clear();
        Notifier::clear();
    }

    private function getUserSuggestions(User $userModel, ?int $excludeId): array
    {
        $allUsers = $this->userModel->all($excludeId);
        shuffle($allUsers);
        $suggestions = array_slice($allUsers, 0, 3);
        
        if ($excludeId) {
            foreach ($suggestions as &$user) {
                $user['follow_status'] = $this->followModel->getFollowStatus($excludeId, $user['id']);
            }
        }

        return $suggestions;
    }

    private function enrichPosts(array $posts, ?array $user): array
    {
        foreach ($posts as &$post) {
            $post['user'] = $this->userModel->getById($post['user_id']); 
            
            $post['liked_by_user'] = $user 
                ? $this->likeModel->userAlreadyLiked($post['id'], $user['id'])
                : false;
            $post['comments'] = $this->commentModel->getByPostId($post['id']);
            $post['time_ago'] = $this->getTimeAgo($post['created_at']);

            foreach ($post['comments'] as &$comment) {
                $comment['time_ago'] = $this->getTimeAgo($comment['created_at']);
            }

        }

        return $posts;
    }

}
