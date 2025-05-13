<?php


namespace App\Controllers;

use App\Core\{ Auth, Controller, Validator, ValidationMessage, Notification };

use App\Models\{ User, Country, Province, Post, Like, Comment, ProfileView };

use App\Traits\{ TimeAgoTrait, SanitizerTrait, FlashMessageTrait };

class UserController extends Controller
{
    use TimeAgoTrait, SanitizerTrait, FlashMessageTrait;

    public function profile()
    {
        if (!Auth::check()) {
            header('Location: /auth/login');
            exit;
        }
    
        $user = Auth::user();
        $countryModel = new Country();
        $provinceModel = new Province();
        $postModel = new Post();
        $likeModel = new Like();
        $commentModel = new Comment();

        $posts = $this->enrichPosts($postModel->getByUserId($user['id']), $user);

        $validationMessages = $this->getValidationMessages();
        $notificationMessages = $this->getNotificationMessages();

        $this->view('users.profile', array_merge([
            'title' => 'Perfil',
            'user' => $user,
            'countries' => $countryModel->getAll(),
            'provinces' => $provinceModel->getAll(),
            'posts' => $posts ?? [],
            'notifications' => $notificationMessages
        ], $validationMessages), 'profile');

        ValidationMessage::clear();
        Notification::clear();
    }

    public function update()
    {
        if (!Auth::check()) {
            header('Location: /auth/login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = Auth::user();
            $userModel = new User();
            $validator = new Validator();

            $currentUser = $userModel->findByEmail($user['email']);
            $input = $this->sanitize($_POST);
            $dataToUpdate = $this->prepareUpdateData($input, $user);

            $rules = $this->getValidationRules($dataToUpdate);
            $validator->validate($dataToUpdate, $rules);

            $this->handlePasswordChange($input, $currentUser, $dataToUpdate);
            
            if ($validator->fails()) {
                $_SESSION['show_modal'] = true;
                
                foreach ($validator->errors() as $error) {
                    ValidationMessage::add($error['field'], $error['message']);
                }
                
                header('Location: /user/profile');
                exit;
            }
            
            $this->handleImageUpload($user, $dataToUpdate, 'imageInput', 'profile_image');
            $this->handleImageUpload($user, $dataToUpdate, 'backgroundImageInput', 'profile_background');

            if (!$userModel->update($user['id'], $dataToUpdate)) {
                Notification::error('Hubo un problema al actualizar el perfil.');

                header('Location: /user/profile');
                exit;
            }
    
            Auth::refresh(array_merge($user, $dataToUpdate));
            Notification::add('success','Perfil actualizado correctamente.');

            header('Location: /user/profile');
            exit;
        }
    }

    public function publicProfile($id)
    {
        $currentUser = Auth::user();
        $userModel = new User();
        $user = $userModel->getByUserId($id);

        if (!$user) {
            http_response_code(404);
            echo "Usuario no encontrado";
            return;
        }

        if ($currentUser && $currentUser['id'] !== $user['id']) {
            $this->trackProfileView($user['id'], $currentUser['id']);
        }
        
        $province = $user['id_province'] ? (new Province())->getById($user['id_province']) : null;
        $country = $province ? (new Country())->getById($province['country_id']) : null;

        $postModel = new Post();
        $likeModel = new Like();
        $commentModel = new Comment();

        $posts = $postModel->getByUserId($user['id']);
        $user['posts_count'] = $postModel->countByUserId($user['id']);

        foreach ($posts as &$post) {
            $post['liked_by_user'] = $likeModel->userAlreadyLiked($post['id'], $user['id']);
            $post['comments'] = $commentModel->getByPostId($post['id']);
            $post['time_ago'] = $this->getTimeAgo($post['created_at']);

            foreach ($post['comments'] as &$comment) {
                $comment['time_ago'] = $this->getTimeAgo($comment['created_at']);
            }

        }

        $this->view('users.profile', [
            'title' => $user['name'],
            'user' => $user,
            'currentUser' => $currentUser,
            'country' => $country ?? '',
            'province' => $province ?? '',
            'posts' => $posts ?? [],
        ], 'profile');
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

                $province = (new Province())->getById($data['id_province']);
                $country = (new Country())->getById($data['id_country']);

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

        $validator = new Validator();

        if ($oldPassword === '' || $newPassword === '') {
            if ($oldPassword === '') {
                $validator->addError('old_password', 'Debes ingresar tu contraseña actual.');
            }
            if ($newPassword === '') {
                $validator->addError('new_password', 'Debes ingresar una nueva contraseña.');
            }
        } else {
            $validator->validate([
                'old_password' => $oldPassword,
                'new_password' => $newPassword,
            ], [
                'old_password' => 'required|min:6|max:20',
                'new_password' => 'required|min:6|max:20',
            ]);

            if (!password_verify($oldPassword, $currentUser['password'])) {
                $validator->addError('old_password', 'La contraseña actual es incorrecta.');
            }
        }

        if ($validator->fails()) {
            foreach ($validator->errors() as $error) {
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

    private function handleImageUpload(array $user, array &$update, string $fieldName, string $columnName)
    {
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) return;

        $uploadDir = __DIR__ . '/../../public/uploads/users/user_' . $user['id'] . '/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $originalName = basename($_FILES[$fieldName]['name']);
        $tmpPath = $_FILES[$fieldName]['tmp_name'];
        $filePrefix = $columnName === 'profile_background' ? 'background_' : '';
        $fileName = "{$user['name']}_{$user['lastname']}_{$filePrefix}{$originalName}";
        $destination = $uploadDir . $fileName;

        if (move_uploaded_file($tmpPath, $destination)) {
            $update[$columnName] = $fileName;
        } else {
            FlashMessage::error("Error al subir el archivo: $columnName");
        }
    }

    private function trackProfileView(int $viewedId, int $viewerId)
    {
        $viewModel = new ProfileView();

        if (!$viewModel->hasViewed($viewedId, $viewerId)) {
            $viewModel->addView($viewedId, $viewerId);
            (new User())->incrementProfileViewCount($viewedId);
        }
    }
}
