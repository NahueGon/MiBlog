<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Post extends Model
{
    public function create(?string $body, ?string $file, string $created_at, string $user_id): int|false
    {
        $query = "INSERT INTO posts (body, file, created_at, user_id) VALUES (:body, :file, :created_at, :user_id)";
        $stmt = $this->db->prepare($query);

        if ($stmt->execute([
            'body'     => $body,
            'file'     => $file,
            'created_at' => $created_at,
            'user_id' => $user_id,
        ])) {
            return (int) $this->db->lastInsertId();
        }
        
        return false;
    }

    public function updateFile($postId, $fileName)
    {
        $sql = "UPDATE posts SET file = :file WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':file' => $fileName,
            ':id' => $postId
        ]);
    }

    public function update(int $id, array $fields): bool {
        if (empty($fields)) return true;
    
        $setParts = [];
        $params = ['id' => $id];
    
        foreach ($fields as $key => $value) {
            $setParts[] = "$key = :$key";
        }
    
        $setClause = implode(', ', $setParts);
        $query = "UPDATE posts SET $setClause WHERE id = :id";
        $stmt = $this->db->prepare($query);
    
        return $stmt->execute($params);
    }

    public function incrementLikesCount($postId)
    {
        $query = "UPDATE posts SET likes_count = likes_count + 1 WHERE id = ?";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([$postId]);
    }

    public function decrementLikesCount($postId)
    {
        $query = "UPDATE posts SET likes_count = GREATEST(likes_count - 1, 0) WHERE id = ?";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([$postId]);
    }

    public function incrementCommentsCount($postId)
    {
        $query = "UPDATE posts SET comments_count = comments_count + 1 WHERE id = ?";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([$postId]);
    }

    public function getByUserId(int $userId): array
    {
        $query = "SELECT posts.*, 
                        users.name, 
                        users.lastname, 
                        users.profile_image
                FROM posts
                JOIN users ON posts.user_id = users.id
                WHERE posts.user_id = :user_id
                ORDER BY posts.created_at DESC
                LIMIT 5";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function all(): array
    {
        $query = "SELECT * FROM posts ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $posts ?: [];
    }

    public function allWithAuthors()
    {
        $query = "SELECT posts.*, users.name, users.lastname, users.profile_image, users.id AS user_id
                FROM posts
                JOIN users ON posts.user_id = users.id
                ORDER BY posts.created_at DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countByUserId(int $userId): int
    {
        $query = "SELECT COUNT(*) as count FROM posts WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['count'] ?? 0);
    }
}
