<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Country extends Model
{
    protected string $table = 'countries';

    public function create(string $name): bool
    {
        $query = "INSERT INTO {$this->table} (name) VALUES (:name)";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            'name'     => $name
        ]);
    }

    public function getAll(): array
    {
        $query = "SELECT * FROM {$this->table} ORDER BY name ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $countries ?: null;
    }

    public function getById(int $id): ?array
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);

        $country = $stmt->fetch(PDO::FETCH_ASSOC);
        return $country ?: null;
    }

    public function exists(string $name): bool
    {
        $query = "SELECT COUNT(*) FROM {$this->table} WHERE name = :name";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['name' => $name]);

        return $stmt->fetchColumn() > 0;
    }
}
