<?php

namespace App\Controllers;

use App\Core\{ Auth, Controller, Validator, ValidationMessage, Notification };

use App\Models\{ User, Country, Post, Province, ProfileView };

use App\Traits\{ FlashMessageTrait, SanitizerTrait };

class AuthController extends Controller
{
    use FlashMessageTrait, SanitizerTrait;

    public function login()
    {
        if (Auth::check()) {
            header('Location: /');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = $this->sanitize($_POST);
            
            $validator = new Validator();
            
            $validator->validate($input, [
                'email' => 'required|string|email|min:6|max:100',
                'password' => 'required|min:6|max:20'
            ]);

            $_SESSION['old_input'] = $input;

            if ($validator->fails()) {
                foreach ($validator->errors() as $error) {
                    ValidationMessage::add($error['field'], $error['message']);
                }
                
                header('Location: /auth/login');
                exit;
            }

            $userModel = new User();
            $user = $userModel->verifyCredentials($input['email'], $input['password']);

            if (!$user) {
                ValidationMessage::add('credentials', 'Credenciales incorrectas');
                Notification::add('error', 'Credenciales incorrectas');

                header('Location: /auth/login');
                exit;
            }
            
            Notification::add('success', 'Haz Inciado Sesion');

            unset($_SESSION['old_input']);
            $this->initializeSession($user);

            header('Location: /auth/login');
            exit;
        }

        $this->renderWithFlash('auth.login', 'Login');
    }

    public function register()
    {
        if (Auth::check()) {
            header('Location: /');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = $this->sanitize($_POST);

            $validator = new Validator();
            
            $validator->validate($input, [
                'name' => 'required|string|min:3|max:50',
                'lastname' => 'required|string|min:3|max:50',
                'email' => 'required|string|email|unique|min:6|max:100',
                'password' => 'required|min:6|max:20',
                'repeat_password' => 'required|min:6|max:20|same:password',
            ]);

            $_SESSION['old_input'] = $input;

            if ($validator->fails()) {
                foreach ($validator->errors() as $error) {
                    ValidationMessage::add($error['field'], $error['message']);
                }
                
                header('Location: /auth/register');
                exit;
            }

            $userModel = new User();
            $userId = $userModel->create($input);
            
            if (!$userId) {
                Notification::add('error', 'Error al registrar el usuario');
  
                header('Location: /auth/register');
                exit;
            }

            $this->createUserDirectory($userId);

            Notification::add('success', [
                'text' => 'Usuario registrado exitosamente',
                'redirect' => '/auth/login'
            ]);

            header('Location: /auth/register');
            exit;
        }

        $this->renderWithFlash('auth.register', 'Register');
    }

    public static function logout(): void
    {  
        $_SESSION = [];

        session_destroy();

        header('Location: /');
        exit;
    }

    private function renderWithFlash(string $view, string $title): void
    {
        $validationMessages = $this->getValidationMessages();
        $notificationMessages = $this->getNotificationMessages();

        $oldInput = $_SESSION['old_input'] ?? [];

        $this->view($view, array_merge([
            'title' => $title,
            'old' => $oldInput,
            'notifications' => $notificationMessages
        ], $validationMessages), 'auth');

        unset($_SESSION['old_input']);

        ValidationMessage::clear();
        Notification::clear();
    }

    private function createUserDirectory(int $userId): void
    {
        $uploadDir = __DIR__ . "/../../public/uploads/users/user_$userId";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
    }

    private function initializeSession(array $user): void
    {
        $provinceModel = new Province();
        $countryModel = new Country();
        $postModel = new Post();
        $profileViewModel = new ProfileView();

        $province = $country = null;

        if (!empty($user['id_province']) && is_int($user['id_province'])) {
            $province = $provinceModel->getById($user['id_province']);
            if ($province && isset($province['country_id'])) {
                $country = $countryModel->getById($province['country_id']);
            }
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_lastname'] = $user['lastname'];
        $_SESSION['user_description'] = $user['description'];
        $_SESSION['user_email'] = $user['email'];

        $_SESSION['user_country'] = $country ? [
            'id' => $country['id'],
            'name' => $country['name']
        ] : null;
        $_SESSION['user_province'] = $province ? [
            'id' => $province['id'],
            'name' => $province['name']
        ] : null;

        $_SESSION['user_profile_image'] = $user['profile_image'];
        $_SESSION['user_profile_background'] = $user['profile_background'];

        $_SESSION['user_post_count'] = $postModel->countByUserId($user['id']);
        $_SESSION['user_views_count'] = $profileViewModel->countByUserId($user['id']);
    }
}
