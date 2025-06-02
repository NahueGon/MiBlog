<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Notification extends Model
{
    protected string $table = 'notifications';
    
    public function create(int $userId, string $type, array $data): bool
    {
        $query = "INSERT INTO {$this->table} (user_id, type, data) VALUES (:user_id, :type, :data)";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            'user_id' => $userId,
            'type'    => $type,
            'data'    => json_encode($data),
        ]);
    }

    public function delete($notificationId)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :notification_id";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            'notification_id' => $notificationId,
        ]);
    }

    public function getByUser(int $userId, int $limit = 10): array
    {
        $query = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAsRead(int $notificationId): bool
    {
        $query = "UPDATE {$this->table} SET is_read = 1 WHERE id = :id";
        $stmt = $this->db->prepare($query);

        return $stmt->execute(['id' => $notificationId]);
    }

    public function countUnread(int $userId): int
    {
        $query = "SELECT COUNT(*) FROM {$this->table} WHERE user_id = :user_id AND is_read = 0";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['user_id' => $userId]);

        return (int) $stmt->fetchColumn();
    }
}
