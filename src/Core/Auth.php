<?php

namespace App\Core;

class Auth
{
    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function user(): ?array
    {
        if (self::check()) {
            return [
                'id'   => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'] ?? null,
                'lastname' => $_SESSION['user_lastname'] ?? null,
                'description' => $_SESSION['user_description'] ?? null,
                'country'    => $_SESSION['user_country'] ?? null,
                'province'    => $_SESSION['user_province'] ?? null,
                'email' => $_SESSION['user_email'] ?? null,
                'profile_image' => $_SESSION['user_profile_image'] ?? null,
                'background_image' => $_SESSION['user_background_image'] ?? null,
                'posts_count' => $_SESSION['user_post_count'] ?? 0,
                'views_count' => $_SESSION['user_views_count'] ?? 0,
                'followers_count' => $_SESSION['user_followers_count'] ?? 0,
                'follows_count' => $_SESSION['user_follows_count'] ?? 0,
            ];
        }

        return null;
    }

    public static function refresh(array $data): void
    {
        $_SESSION['user_name'] = $data['name'];
        $_SESSION['user_lastname'] = $data['lastname'];
        $_SESSION['user_description'] = $data['description'];
        $_SESSION['user_email'] = $data['email'];

        $_SESSION['user_country'] = [
            'id' => $data['id_country'] ?? ($data['country']['id'] ?? null),
            'name' => is_array($data['country']) ? $data['country']['name'] : $data['country']
        ];
        $_SESSION['user_province'] = [
            'id' => $data['id_province'] ?? ($data['province']['id'] ?? null),
            'name' => is_array($data['province']) ? $data['province']['name'] : $data['province']
        ];

        $_SESSION['user_profile_image'] = $data['profile_image'];
        $_SESSION['user_background_image'] = $data['background_image'];

        $_SESSION['user_post_count'] = $data['posts_count'];
        $_SESSION['user_followers_count'] = $data['followers_count'];
        $_SESSION['user_follows_count'] = $data['follows_count'];
    }
}
