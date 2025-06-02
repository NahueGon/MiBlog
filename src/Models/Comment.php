<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Comment extends Model
{
    protected string $table = 'comments';

    public function addComment($postId, $userId, $content)
    {
        $query = "INSERT INTO {$this->table} (post_id, user_id, content) VALUES (:post_id, :user_id, :content)";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
           'post_id' => $postId,
           'user_id' => $userId,
           'content' => $content
        ]);
    }

    public function getById($commentId)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :commentId");
        $stmt->execute([
            'commentId' => $commentId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function removeComment($commentId)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :commentId";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            'commentId' => $commentId
        ]);
    }

    public function getByPostId($postId)
    {
        $query = "SELECT c.*, u.name as user_name,
                            u.lastname as user_lastname, 
                            u.description as user_description,
                            u.profile_image as user_image,
                            u.id as user_id
                FROM {$this->table} c 
                JOIN users u ON c.user_id = u.id
                WHERE c.post_id = :post_id
                ORDER BY c.created_at ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'post_id' => $postId
        ]);
        
        return $stmt->fetchAll();
    }
}
