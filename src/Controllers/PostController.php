<?php

namespace App\Controllers;

use App\Core\{ Auth, Controller, Validator, ValidationMessage, Notification };

use App\Models\{ User, Post, Like, Comment, };

use App\Traits\{ TimeAgoTrait, FlashMessageTrait };

class PostController extends Controller
{
    use TimeAgoTrait; 

    public function index()
    {
        $user = Auth::user();
        $postModel = new Post();
        $likeModel = new Like();
        $commentModel = new Comment();

        $posts = $this->enrichPosts($postModel->getByUserId($user['id']), $user);

        $this->view('posts.index', [
            'title' => 'Publicaciones',
            'user' => $user,
            'posts' => $posts ?? [],
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = Auth::user();
            $postModel = new Post();

            $body = trim($_POST['body']);
            $file = null;
            $date = date('Y-m-d H:i:s');
            $user_id = $user['id'];

            $postsId = $postModel->create($body, $file, $date, $user_id);
            
            if ($postsId) {

                $uploadDir = __DIR__ . '/../../public/uploads/users/user_' . $user['id'] . '/posts/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                if (isset($_FILES['fileInput']) && $_FILES['fileInput']['error'] === UPLOAD_ERR_OK) {

                    $originalName = $_FILES['fileInput']['name'];
                    $tmpPath = $_FILES['fileInput']['tmp_name'];
                    $fileName = uniqid() . '_' . basename($originalName);
                    $destination = $uploadDir . $fileName;

                    if (move_uploaded_file($tmpPath, $destination)) {
                        $file = $fileName;
                        $postModel->updateFile($postsId, $file);
                    } else {
                        Notification::add('error', 'Error al subir el archivo');
                        header('Location: /');
                        exit;
                    }
                }

                Notification::add('success', 'Post creado exitosamente');
                header('Location: /');
                exit;
            } else {
                Notification::add('error', 'Error al crear el post');
                header('Location: /');
                exit;
            }
        }

    }

    public function toggleLike($postId)
    {
        $user = Auth::user();
        $likeModel = new Like();
        $postModel = new Post();

        if ($likeModel->userAlreadyLiked($postId, $user['id'])) {
            $likeModel->removeLike($postId, $user['id']);
            $postModel->decrementLikesCount($postId);
        } else {
            $likeModel->addLike($postId, $user['id']);
            $postModel->incrementLikesCount($postId);
        }

        header('Location: /');
        exit;
    }

    public function addComment($postId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = Auth::user();
            $commentModel = new Comment();
            $postModel = new Post();
            
            $content = trim($_POST['postComment']);
            $date = date('Y-m-d H:i:s');

            $commentModel->addComment($postId, $user['id'], $content, $date);
            $postModel->incrementCommentsCount($postId);
            
            header('Location: /');
            exit;
        }
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
