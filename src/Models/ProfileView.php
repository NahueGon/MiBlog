<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class ProfileView extends Model
{
    public function hasViewed(int $userId, int $viewerId): bool 
    {
        $query = "SELECT 1 FROM profile_views WHERE user_id = :user_id AND viewer_id = :viewer_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'user_id' => $userId,
            'viewer_id' => $viewerId
        ]);
        
        return (bool) $stmt->fetch();
    }

    public function addView(int $userId, int $viewerId): bool 
    {
        $query = "INSERT INTO profile_views (user_id, viewer_id) VALUES (:user_id, :viewer_id)";
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute([
            'user_id' => $userId,
            'viewer_id' => $viewerId
        ]);
    }

    public function countByUserId(int $userId): int
    {
        $query = "SELECT COUNT(*) as count FROM profile_views WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'user_id' => $userId
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) ($result['count'] ?? 0);
    }
}
