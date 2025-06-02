<?php

namespace App\Controllers;

use App\Core\{ Auth, Controller, Validator, ValidationMessage, Notifier };

use App\Models\{ User, Post, Like, Comment, Notification };

use App\Traits\{ TimeAgoTrait, SanitizerTrait, FlashMessageTrait, handleImageUpload };

class PostController extends Controller
{
    use TimeAgoTrait, SanitizerTrait, FlashMessageTrait, handleImageUpload;

    private $userModel;
    private $postModel;
    private $likeModel;
    private $commentModel;
    private $notificationModel;
    private $validator;

    public function __construct()
    {
        $this->userModel = new User();
        $this->postModel = new Post();
        $this->likeModel = new Like();
        $this->commentModel = new Comment();
        $this->notificationModel = new Notification();
        $this->validator = new Validator();
    }

    public function index($id)
    {
        $user = Auth::user();

        $parsedUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $posts = $this->enrichPosts($this->postModel->getAllByUserId($id), $user);

        $this->view('posts.index', [
            'title' => 'Publicaciones',
            'user' => $user,
            'url' => $parsedUrl,
            'posts' => $posts ?? [],
        ]);
    }

    public function show($id)
    {
        $user = Auth::user();

        $post = $this->postModel->getById($id);
        $currentUser = $this->userModel->getById($post['user_id']);

        $post['user'] = $currentUser;
        $post['liked_by_user'] = $user ? $this->likeModel->userAlreadyLiked($post['id'], $user['id']) : false;
        $post['comments'] = $this->commentModel->getByPostId($post['id']);
        $post['time_ago'] = $this->getTimeAgo($post['created_at']);

        foreach ($post['comments'] as &$comment) {
            $comment['time_ago'] = $this->getTimeAgo($comment['created_at']);
        }

        $notificationMessages = $this->getNotificationMessages();

        $this->view('posts.show', [
            'title' => 'Publicacion',
            'user' => $user,
            'currentUser' => $currentUser,
            'post' => $post ?? [],
            'notifications' => $notificationMessages
        ]);

        Notifier::clear();
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = Auth::user();
            $input = $this->sanitize($_POST);
            $input['user_id'] = $user['id'];
            $file = $_FILES;

            if ($file['file']['error'] === UPLOAD_ERR_NO_FILE && empty($input['body'])) {
                $this->validator->validate($input, [
                    'body' => 'min:3|max:5000',
                ]);
                
                if ($this->validator->fails()) {
                    $_SESSION['show_modal'] = true;
                    
                    foreach ($this->validator->errors() as $error) {
                        ValidationMessage::add($error['field'], $error['message']);
                    }
                    
                    header('Location: /');
                    exit;
                }
                
            }

            $postId = $this->postModel->create($input);

            if ($file['file']['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($file['file']['error'] !== UPLOAD_ERR_OK){
                    Notifier::add('error','Hubo un problema al subir la imagen del post.');
                    
                    header('Location: /');
                    exit;
                }
    
                if ($file['file']['error'] === UPLOAD_ERR_OK){
                    $this->handleImageUpload($user['id'], $file, 'posts');
                    $this->postModel->update($postId, $file);
                }
            }

            Notifier::add('success', 'Post creado exitosamente');
            header('Location: /');
            exit;
        }
    }

    public function delete($id)
    {
        $user = Auth::user();

        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $parsedUrl = parse_url($referer, PHP_URL_PATH);

        $post = $this->postModel->getById($id);

        if (!$post) {
            Notifier::add('error', 'Post no encontrado');
            header('Location: /');
            exit;
        }

        $isAuthor = $this->postModel->isAuthor($post['id'], $user['id']);

        if (!$isAuthor){
            Notifier::add('info', 'No autorizado');

            header('Location: /post/show/' . $post['id']);
            exit;
        }

        if (isset($post['file'])) {
            $this->handleImageDelete($user['id'], $post['file'], 'posts');
        }

        $this->postModel->delete($id);
        Notifier::add('success', 'Post eliminado correctamente');
        header('Location: ' . $parsedUrl);
        exit;
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
