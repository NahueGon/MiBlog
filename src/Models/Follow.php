<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Follow extends Model
{
    protected string $table = 'follows';

    public function followUser(int $followerId, int $followingId): bool
    {
        $validationQuery = "SELECT COUNT(*) FROM {$this->table} WHERE follower_id = :follower_id AND following_id = :following_id";
        $stmt = $this->db->prepare($validationQuery);
        $stmt->execute([
            'follower_id' => $followerId,
            'following_id' => $followingId
        ]);
        if ($stmt->fetchColumn() > 0) return false;

        $query = "INSERT INTO {$this->table} (follower_id, following_id, status) VALUES (:follower_id, :following_id, 'pending')";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            'follower_id' => $followerId,
            'following_id' => $followingId
        ]);
    }

    public function respondRequest(int $followerId, int $followingId, string $status): bool
    {
        if (!in_array($status, ['accepted', 'rejected'])) return false;

        $query = "UPDATE {$this->table} SET status = :status WHERE follower_id = :follower_id AND following_id = :following_id";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            'status' => $status,
            'follower_id' => $followerId,
            'following_id' => $followingId
        ]);
    }

    public function getPendingRequests(int $userId): array
    {
        $query = "SELECT users.id, users.name, users.lastname, users.description, users.profile_image FROM {$this->table} 
                JOIN users ON users.id = {$this->table}.follower_id
                WHERE {$this->table}.following_id = :user_id AND {$this->table}.status = 'pending'";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function unfollowUser(int $followerId, int $followingId): bool
    {
        $query = "DELETE FROM {$this->table} WHERE follower_id = :follower_id AND following_id = :following_id";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            'follower_id' => $followerId,
            'following_id' => $followingId
        ]);
    }

    public function isFollowing(int $followerId, int $followingId): bool
    {
        $query = "SELECT COUNT(*) FROM {$this->table} WHERE follower_id = :follower_id AND following_id = :following_id AND status = 'accepted'";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'follower_id' => $followerId,
            'following_id' => $followingId
        ]);

        return $stmt->fetchColumn() > 0;
    }

    public function getFollowStatus(int $followerId, int $followingId): ?string
    {
        $query = "SELECT status FROM {$this->table} WHERE follower_id = :follower_id AND following_id = :following_id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'follower_id' => $followerId,
            'following_id' => $followingId
        ]);

        return $stmt->fetchColumn() ?: null;
    }

    public function cancelRequest(int $followerId, int $followingId): bool
    {
        $query = "DELETE FROM {$this->table} WHERE follower_id = :follower_id AND following_id = :following_id AND status = 'pending'";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'follower_id' => $followerId,
            'following_id' => $followingId
        ]);
    }

    public function getFollowers(int $userId): array
    {
        $query = "SELECT users.* FROM users INNER JOIN {$this->table} ON users.id = {$this->table}.follower_id
                WHERE {$this->table}.following_id = :user_id AND {$this->table}.status = 'accepted'";
        $stmt = $this->db->prepare();
        $stmt->execute([
            'user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFollowing(int $userId): array
    {
        $query = "SELECT users.* FROM users INNER JOIN {$this->table} ON users.id = {$this->table}.following_id
                WHERE {$this->table}.follower_id = :user_id AND {$this->table}.status = 'accepted'";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countFollowers(int $userId): int
    {
        $query = "SELECT COUNT(*) FROM {$this->table} WHERE following_id = :user_id AND status = 'accepted'";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'user_id' => $userId
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function countFollowing(int $userId): int
    {
        $query = "SELECT COUNT(*) FROM {$this->table} WHERE follower_id = :user_id AND status = 'accepted'";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'user_id' => $userId
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function deleteRequest(int $followerId, int $followingId): bool
    {
        $query = "DELETE FROM {$this->table} WHERE follower_id = :follower_id AND following_id = :following_id";
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute([
            'follower_id' => $followerId,
            'following_id' => $followingId
        ]);
    }
}
