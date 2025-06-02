<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model
{
    protected string $table = 'users';

    public function create(array $data): int|false
    {
        $query = "INSERT INTO {$this->table} (name, lastname, email, password) VALUES (:name, :lastname, :email, :password)";
        $stmt = $this->db->prepare($query);

        if ($stmt->execute([
            'name'     => $data['name'],
            'lastname' => $data['lastname'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ])) {
            return (int) $this->db->lastInsertId();
        }
        
        return false;
    }

    public function update(int $id, array $fields): bool {
        unset($fields['id_country'], $fields['country'], $fields['province']);

        if (empty($fields)) return true;
    
        $setParts = [];
        $params = ['id' => $id];
    
        foreach ($fields as $key => $value) {
            $setParts[] = "$key = :$key";

            $params[$key] = ($key === 'password') ? password_hash($value, PASSWORD_DEFAULT) : $value;
        }
    
        $setClause = implode(', ', $setParts);
        $query = "UPDATE {$this->table} SET $setClause WHERE id = :id";
        $stmt = $this->db->prepare($query);
    
        return $stmt->execute($params);
    }

    public function findByEmail(string $email): ?array
    {
        $query = "SELECT * FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'email' => $email
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function existsEmailInAnotherUser($email, $currentUserId)
    {
        $query = "SELECT id FROM {$this->table} WHERE email = :email AND id != :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'email' => $email,
            'id' => $currentUserId
        ]);

        return $stmt->fetch() !== false;
    }

    public function verifyCredentials(string $email, string $password): ?array
    {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return null;
    }

    public function getById(int $userId): array
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :userId";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'userId' => $userId
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function all(?int $excludedId = null): array
    {
        if ($excludedId !== null) {
            $query = "SELECT * FROM {$this->table} WHERE id != :excludedId";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':excludedId', $excludedId, PDO::PARAM_INT);
        } else {
            $query = "SELECT * FROM {$this->table}";
            $stmt = $this->db->prepare($query);
        }

        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $users ?: [];
    }

    public function incrementProfileViewCount(int $userId): bool
    {
        $query = "UPDATE {$this->table} SET profile_views_count = profile_views_count + 1 WHERE id = :id";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            'id' => $userId
        ]);
    }
}
