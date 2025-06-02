<?php

namespace App\Controllers;

use App\Core\{ Auth, Controller, Validator, ValidationMessage, Notifier };

use App\Models\{ User, Post, Comment, Notification };

use App\Traits\{ TimeAgoTrait, SanitizerTrait, FlashMessageTrait };

class CommentController extends Controller
{
    use TimeAgoTrait, SanitizerTrait, FlashMessageTrait; 

    private $userModel;
    private $postModel;
    private $commentModel;
    private $notificationModel;
    private $validator;

    public function __construct()
    {
        $this->userModel = new User();
        $this->postModel = new Post();
        $this->commentModel = new Comment();
        $this->notificationModel = new Notification();
        $this->validator = new Validator();
    }

    public function create($postId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = Auth::user();
            
            $input = $this->sanitize($_POST);
            $this->validator->validate($input, [
                'postComment' => 'required|min:3|max:5000',
            ]);
            
            if ($this->validator->fails()) {

                foreach ($this->validator->errors() as $error) {
                    ValidationMessage::add($error['field'], $error['message']);
                }
                
                header('Location: /');
                exit;
            }

            $this->commentModel->addComment($postId, $user['id'], $input['postComment']);
            $this->postModel->incrementCommentsCount($postId);
 
            $post = $this->postModel->getById($postId);

            
            if ($post && $post['user_id'] !== $user['id']) {
                $this->notificationModel->create($post['user_id'], 'comment', [
                    'from_user_id' => $user['id'],
                    'post_id'      => $postId,
                    'content'      => $input['postComment']
                ]);
            }
            
            header('Location: /post/show/'. $postId);
            exit;
        }
    }

    public function delete($commentId)
    {
        $user = Auth::user();

        $comment = $this->commentModel->getById($commentId);
        if (!$comment) {
            header('Location: /');
            exit;
        }

        $post = $this->postModel->getById($comment['post_id']);

        $isAuthorOfComment = $user['id'] === $comment['user_id'];
        $isAuthorOfPost = $user['id'] === $post['user_id'];

        if (!$isAuthorOfComment && !$isAuthorOfPost) {

            Notifier::add('info', 'No autorizado');
            header('Location: /post/show/' . $comment['post_id']);
            exit;
        }

        $this->commentModel->removeComment($commentId);
        $this->notificationModel->delete($comment['id']);
        $this->postModel->decrementCommentsCount($comment['post_id']);

        header('Location: /post/show/' . $comment['post_id']);
        exit;
    }
}