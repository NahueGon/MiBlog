<?php


namespace App\Controllers;

use App\Core\{ Auth, Controller, Validator, ValidationMessage, Notifier };

use App\Models\{ User, Country, Province, Post, Like, Comment, ProfileView, Follow, Notification };

use App\Traits\{ TimeAgoTrait, SanitizerTrait, FlashMessageTrait, handleImageUpload };

class UserController extends Controller
{
    use TimeAgoTrait, SanitizerTrait, FlashMessageTrait, handleImageUpload;

    private $userModel;
    private $postModel;
    private $countryModel;
    private $provinceModel;
    private $viewModel;
    private $likeModel;
    private $commentModel;
    private $followModel;
    private $notificationModel;
    private $validator;

    public function __construct()
    {
        $this->userModel = new User();
        $this->postModel = new Post();
        $this->countryModel = new Country();
        $this->provinceModel = new Province();
        $this->likeModel = new Like();
        $this->commentModel = new Comment();
        $this->viewModel = new ProfileView();
        $this->followModel = new Follow();
        $this->notificationModel = new Notification();
        $this->validator = new Validator();
    }

    public function profile($id = null)
    {
        if (!Auth::check()) {
            header('Location: /auth/login');
            exit;
        }

        $currentUser = Auth::user();

        $user = $id ? $this->userModel->getById($id) : $currentUser;

        if (!$user) {
            http_response_code(404);
            echo "Usuario no encontrado";
            return;
        }

        if ($id && $currentUser['id'] !== $user['id']) {
            $this->trackProfileView($user['id'], $currentUser['id']);
        }

        $province = isset($user['province']['id']) && $user['province']['id']
                    ? $this->provinceModel->getById($user['province']['id'])
                    : null;
        $country = $province ? $this->countryModel->getById($province['country_id']) : null;
        
        $user['province'] = $province;
        $user['country'] = $country;
        
        $allPosts = $this->enrichPosts($this->postModel->getAllByUserId($user['id']), $user);
        $posts = array_slice($allPosts, 0, 5);

        $user['posts_count'] = $this->postModel->countByUserId($user['id']);
        $user['views_count'] = $this->viewModel->countByUserId($user['id']);

        $user['follow_status'] = $currentUser['id'] !== $user['id']
            ? $this->followModel->getFollowStatus($currentUser['id'], $user['id'])
            : null;

        $user['followers_count'] = $this->followModel->countFollowers($user['id']);
        $user['follows_count'] = $this->followModel->countFollowing($user['id']);

        $validationMessages = $this->getValidationMessages();
        $notificationMessages = $this->getNotificationMessages();

        $this->view('users.profile', array_merge([
            'title' => $user['name'] . ' ' . $user['lastname'],
            'user' => $user,
            'currentUser' => $currentUser,
            'posts' => $posts ?? [],
            'notifications' => $notificationMessages,
            'countries' => $this->countryModel->getAll(),
            'provinces' => $this->provinceModel->getAll(),
        ], $validationMessages), 'profile');

        ValidationMessage::clear();
        Notifier::clear();
    }

    public function update()
    {
        if (!Auth::check()) {
            header('Location: /auth/login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = Auth::user();

            $currentUser = $this->userModel->findByEmail($user['email']);
            $input = $this->sanitize($_POST);
            $input['files'] = $_FILES;
            $dataToUpdate = $this->prepareUpdateData($input, $user);

            $rules = $this->getValidationRules($dataToUpdate);
            $this->validator->validate($dataToUpdate, $rules);

            $this->handlePasswordChange($input, $currentUser, $dataToUpdate);
            
            if ($this->validator->fails()) {
                $_SESSION['show_modal'] = true;
                
                foreach ($this->validator->errors() as $error) {
                    ValidationMessage::add($error['field'], $error['message']);
                }
                
                header('Location: /user/profile');
                exit;
            }

            if(!empty($dataToUpdate)){
                if(isset($dataToUpdate['profile_image']) && ($dataToUpdate['profile_image']['error'] !== UPLOAD_ERR_OK)){
                    Notifier::add('error','Hubo un problema al actualizar la imagen de perfil.');
                    
                    header('Location: /user/profile');
                    exit;
                }
                
                if (isset($dataToUpdate['background_image']) && $dataToUpdate['background_image']['error'] !== UPLOAD_ERR_OK) {
                    Notifier::add('error','Hubo un problema al actualizar la imagen background.');
                    
                    header('Location: /user/profile');
                    exit;
                }
                
                $this->handleImageUpload($user['id'], $dataToUpdate);
            }
    
            if (!$this->userModel->update($user['id'], $dataToUpdate)) {
                Notifier::add('error','Hubo un problema al actualizar el perfil.');
                
                header('Location: /user/profile');
                exit;
            }
    
            Auth::refresh(array_merge($user, $dataToUpdate));
            Notifier::add('success','Perfil actualizado correctamente.');

            header('Location: /user/profile');
            exit;
        }
    }

    public function notifications()
    {
        if (!Auth::check()) {
            header('Location: /auth/login');
            exit;
        }

        $user = Auth::user();

        $provinceId = $user['id_province'] ?? null;
        $province = $country = '';

        if ($provinceId !== null && is_int($provinceId)) {
            $province = $this->provinceModel->getById($provinceId);
            $country = $this->countryModel->getById($province['country_id']);
        }

        $notifications = $this->notificationModel->getByUser($user['id']);

        foreach ($notifications as &$notif) {
            $data = json_decode($notif['data'], true);
            $notif['time_ago'] = $this->getTimeAgo($notif['created_at']);

            if (isset($data['from_user_id'])) {
                $fromUser = $this->userModel->getById((int)$data['from_user_id']);
                $notif['from_id'] = $fromUser['id'] ?? '';
                $notif['from_username'] = $fromUser['name'] . ' ' . $fromUser['lastname'] ?? 'Usuario';
                $notif['from_profile_image'] = $fromUser['profile_image'] ?? 'anon-profile.jpg';
            }
        }

        $this->view('users.notifications', [
            'title' => 'Notificaciones',
            'user' => $user,
            'country' => $country,
            'province' => $province,
            'notifications' => $notifications,
        ]);
    }

    public function network()
    {
        if (!Auth::check()) {
            header('Location: /auth/login');
            exit;
        }

        $user = Auth::user();

        $provinceId = $user['id_province'] ?? null;
        $province = $country = '';

        if ($provinceId !== null && is_int($provinceId)) {
            $province = $this->provinceModel->getById($provinceId);
            $country = $this->countryModel->getById($province['country_id']);
        }

        $users = $this->getUserSuggestions($this->userModel, $user['id'] ?? null);
        $pendingRequests = $this->followModel->getPendingRequests($user['id']);

        $notificationMessages = $this->getNotificationMessages();

        $this->view('users.network', [
            'title' => 'Red',
            'user' => $user,
            'users' => $users,  
            'country' => $country,
            'province' => $province,
            'pendingRequests' => $pendingRequests,
            'notifications' => $notificationMessages
        ]);

        Notifier::clear();
    }

    private function getUserSuggestions(User $userModel, ?int $excludeId): array
    {
        $allUsers = $userModel->all($excludeId);
        shuffle($allUsers);
        $suggestions = array_slice($allUsers, 0, 20);
        
        if ($excludeId) {
            foreach ($suggestions as &$user) {
                $user['follow_status'] = $this->followModel->getFollowStatus($excludeId, $user['id']);
            }
        }

        return $suggestions;
    }

    private function getValidationRules(array $dataToUpdate): array
    {
        $rules = [];

        if (isset($dataToUpdate['name'])) {
            $rules['name'] = 'string|min:3|max:50';
        }

        if (isset($dataToUpdate['lastname'])) {
            $rules['lastname'] = 'string|min:3|max:50';
        }

        if (isset($dataToUpdate['email'])) {
            $rules['email'] = 'string|email|unique|min:6|max:100';
        }

        if (isset($dataToUpdate['id_country'])) {
            $rules['id_country'] = 'integer';
            $rules['country'] = 'required';
        }

        if (isset($dataToUpdate['id_province'])) {
            $rules['id_province'] = 'integer';
            $rules['province'] = 'required';
        }

        if (isset($dataToUpdate['new_password'])) {
            $rules['new_password'] = 'min:6|max:20';
        }

        if (isset($dataToUpdate['old_password'])) {
            $rules['old_password'] = 'min:6|max:20';
        }

        return $rules;
    }

    private function prepareUpdateData(array $data, array $currentUser): array
    {
        $update = [];
        
        if ($data['files']['backgroundImageInput']['error'] !== UPLOAD_ERR_NO_FILE) {
            $update['background_image'] = $_FILES['backgroundImageInput'];
        }

        if ($data['files']['profileImageInput']['error'] !== UPLOAD_ERR_NO_FILE) {  
            $update['profile_image'] = $_FILES['profileImageInput'];
        }

        if ($data['name'] !== $currentUser['name']) {
            $update['name'] = $data['name'];
        }
        
        if ($data['lastname'] !== $currentUser['lastname']) {
            $update['lastname'] = $data['lastname'];
        }
        
        if ($data['description'] !== $currentUser['description']) {
            $update['description'] = $data['description'];
        }

        if ($data['id_province'] !== '') {
            if ((int)$data['id_country'] !== (int)$currentUser['country']['id'] || (int)$data['id_province'] !== (int)$currentUser['province']['id']) {

                $province = $this->province->getById($data['id_province']);
                $country = $this->country->getById($data['id_country']);

                $update['id_country'] = $country['id'];
                $update['country'] = $country['name'];
                $update['id_province'] = $province['id'];
                $update['province'] = $province['name'];
                
            }
        } else {
            $data['id_province'] = $currentUser['province']['id'];
            $data['id_country'] = $currentUser['country']['id'];
        }
        
        if ($data['email'] !== $currentUser['email']) {
            $update['email'] = $data['email'];
        }

        return $update;
    }

    private function handlePasswordChange(array $data, array $currentUser, array &$update): void
    {
        $oldPassword = trim($data['old_password'] ?? '');
        $newPassword = trim($data['new_password'] ?? '');

        if ($oldPassword === '' && $newPassword === '') {
            return;
        }

        if ($oldPassword === '' || $newPassword === '') {
            if ($oldPassword === '') {
                $this->validator->addError('old_password', 'Debes ingresar tu contraseña actual.');
            }
            if ($newPassword === '') {
                $this->validator->addError('new_password', 'Debes ingresar una nueva contraseña.');
            }
        } else {
            $this->validator->validate([
                'old_password' => $oldPassword,
                'new_password' => $newPassword,
            ], [
                'old_password' => 'required|min:6|max:20',
                'new_password' => 'required|min:6|max:20',
            ]);

            if (!password_verify($oldPassword, $currentUser['password'])) {
                $this->validator->addError('old_password', 'La contraseña actual es incorrecta.');
            }
        }

        if ($this->validator->fails()) {
            foreach ($this->validator->errors() as $error) {
                ValidationMessage::add($error['field'], $error['message']);
            }
            $_SESSION['show_modal'] = true;
            header('Location: /user/profile');
            exit;
        }

        $update['password'] = $newPassword;
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

    private function trackProfileView(int $viewedId, int $viewerId)
    {
        if (!$this->viewModel->hasViewed($viewedId, $viewerId)) {
            $this->viewModel->addView($viewedId, $viewerId);
            $this->userModel->incrementProfileViewCount($viewedId);
        }
    }
}
