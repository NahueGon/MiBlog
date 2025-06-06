<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Like extends Model
{
    protected string $table = 'likes';

    public function addLike(int $postId, int $userId): bool
    {
        $query = "INSERT INTO {$this->table} (post_id, user_id) VALUES (:post_id, :user_id)";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            'post_id' => $postId,
            'user_id' => $userId
        ]);
    }

    public function removeLike(int $postId, int $userId): bool
    {
        $query = "DELETE FROM {$this->table} WHERE post_id = :post_id AND user_id = :user_id";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            'post_id' => $postId,
            'user_id' => $userId
        ]);
    }

    public function userAlreadyLiked(int $postId, int $userId): bool
    {
        $query = "SELECT COUNT(*) FROM {$this->table} WHERE post_id = :post_id AND user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'post_id' => $postId,
            'user_id' => $userId
        ]);

        return $stmt->fetchColumn() > 0;
    }
}
