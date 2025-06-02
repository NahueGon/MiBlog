<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Post extends Model
{
    protected string $table = 'posts';

    public function create(array $data): int|false
    {
        $query = "INSERT INTO {$this->table} (body, user_id) VALUES (:body, :user_id)";
        $stmt = $this->db->prepare($query);

        if ($stmt->execute([
            'body'     => $data['body'],
            'user_id' => $data['user_id'],
        ])) {
            return (int) $this->db->lastInsertId();
        }
        
        return false;
    }

    public function delete($postsId)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :post_id";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            'post_id' => $postsId,
        ]);
    }

    public function update(int $id, array $fields): bool {
        if (empty($fields)) return true;
        
        $setParts = [];
        $params = ['id' => $id];
        
        foreach ($fields as $key => $value) {
            $setParts[] = "$key = :$key";

            $params[$key] = $value;
        }
        
        $setClause = implode(', ', $setParts);
        $query = "UPDATE {$this->table} SET $setClause WHERE id = :id";
        $stmt = $this->db->prepare($query);
    
        return $stmt->execute($params);
    }

    public function incrementLikesCount($postId)
    {
        $query = "UPDATE {$this->table} SET likes_count = likes_count + 1 WHERE id = ?";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([$postId]);
    }

    public function decrementLikesCount($postId)
    {
        $query = "UPDATE {$this->table} SET likes_count = GREATEST(likes_count - 1, 0) WHERE id = ?";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([$postId]);
    }

    public function incrementCommentsCount($postId)
    {
        $query = "UPDATE {$this->table} SET comments_count = comments_count + 1 WHERE id = ?";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([$postId]);
    }

    public function decrementCommentsCount($postId)
    {
        $query = "UPDATE {$this->table} SET comments_count = GREATEST(comments_count - 1, 0) WHERE id = ?";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([$postId]);
    }

    public function getAllByUserId(int $userId): array
    {
        $query = "SELECT {$this->table}.*, 
                        users.name, 
                        users.lastname, 
                        users.profile_image
                FROM {$this->table}
                JOIN users ON posts.user_id = users.id
                WHERE posts.user_id = :user_id
                ORDER BY posts.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getById(int $postId): ?array
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :post_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'post_id' => $postId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function all(): array
    {
        $query = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $posts ?: [];
    }

    public function allWithAuthors()
    {
        $query = "SELECT {$this->table}.*, users.name, users.lastname, users.profile_image, users.id AS user_id
                FROM {$this->table}
                JOIN users ON posts.user_id = users.id
                ORDER BY posts.created_at DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countByUserId(int $userId): int
    {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['count'] ?? 0);
    }

    public function isAuthor(int $postId, int $userId): bool
    {
        $query = "SELECT 1 FROM {$this->table} WHERE id = :post_id AND user_id = :user_id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'post_id' => $postId,
            'user_id' => $userId
        ]);

        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
