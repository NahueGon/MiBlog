<?php

namespace App\Controllers;

use App\Core\{ Auth, Controller, Validator, ValidationMessage, Notifier };

use App\Models\{ User, Country, Post, Province, ProfileView, Follow };

use App\Traits\{ FlashMessageTrait, SanitizerTrait };

class AuthController extends Controller
{
    use FlashMessageTrait, SanitizerTrait;

    private $userModel;
    private $postModel;
    private $countryModel;
    private $provinceModel;
    private $validator;
    private $profileViewModel;
    private $followModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->postModel = new Post();
        $this->countryModel = new Country();
        $this->provinceModel = new Province();
        $this->validator = new Validator();
        $this->profileViewModel = new ProfileView();
        $this->followModel = new Follow();
    }

    public function login()
    {
        if (Auth::check()) {
            header('Location: /');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = $this->sanitize($_POST);
            
            $this->validator->validate($input, [
                'email' => 'required|string|email|min:6|max:100',
                'password' => 'required|min:6|max:20'
            ]);

            $_SESSION['old_input'] = $input;

            if ($this->validator->fails()) {
                foreach ($this->validator->errors() as $error) {
                    ValidationMessage::add($error['field'], $error['message']);
                }
                
                header('Location: /auth/login');
                exit;
            }

            $user = $this->userModel->verifyCredentials($input['email'], $input['password']);

            if (!$user) {
                ValidationMessage::add('credentials', 'Credenciales incorrectas');
                Notifier::add('error', 'Credenciales incorrectas');

                header('Location: /auth/login');
                exit;
            }
            
            Notifier::add('success', 'Haz Inciado Sesion');

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
            
            $this->validator->validate($input, [
                'name' => 'required|string|min:3|max:50',
                'lastname' => 'required|string|min:3|max:50',
                'email' => 'required|string|email|unique|min:6|max:100',
                'password' => 'required|min:6|max:20',
                'repeat_password' => 'required|min:6|max:20|same:password',
            ]);

            $_SESSION['old_input'] = $input;

            if ($this->validator->fails()) {
                foreach ($this->validator->errors() as $error) {
                    ValidationMessage::add($error['field'], $error['message']);
                }
                
                header('Location: /auth/register');
                exit;
            }

            $userId = $this->userModel->create($input);
            
            if (!$userId) {
                Notifier::add('error', 'Error al registrar el usuario');
  
                header('Location: /auth/register');
                exit;
            }

            $this->createUserDirectory($userId);

            Notifier::add('success', [
                'text' => 'Usuario registrado exitosamente',
                'time' => true,
                'path' => '/auth/login'
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
        Notifier::clear();
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
        $province = $country = null;

        if (!empty($user['id_province']) && is_int($user['id_province'])) {
            $province = $this->provinceModel->getById($user['id_province']);
            if ($province && isset($province['country_id'])) {
                $country = $this->countryModel->getById($province['country_id']);
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
        $_SESSION['user_background_image'] = $user['background_image'];

        $_SESSION['user_post_count'] = $this->postModel->countByUserId($user['id']);
        $_SESSION['user_views_count'] = $this->profileViewModel->countByUserId($user['id']);

        $_SESSION['user_followers_count'] = $this->followModel->countFollowers($user['id']);
        $_SESSION['user_follows_count'] = $this->followModel->countFollowing($user['id']);
    }
}
