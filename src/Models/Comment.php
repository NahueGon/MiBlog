<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Comment extends Model
{
    public function addComment($postId, $userId, $content, $date)
    {
        $query = "INSERT INTO comments (post_id, user_id, content, created_at) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([$postId, $userId, $content, $date]);
    }

    public function removeComment($postId, $userId)
    {
        $query = "DELETE FROM comments WHERE post_id = ? AND user_id = ?";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([$postId, $userId]);
    }

    public function getByPostId($postId)
    {
        $query = "SELECT c.*, u.name as user_name, u.lastname as user_lastname, u.profile_image as user_image, u.id as user_id
                    FROM comments c 
                    JOIN users u ON c.user_id = u.id
                    WHERE c.post_id = :post_id
                    ORDER BY c.created_at ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute(['post_id' => $postId]);
        
        return $stmt->fetchAll();
    }
}
