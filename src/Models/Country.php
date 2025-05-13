<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Country extends Model
{
    public function create(string $name): bool
    {
        $query = "INSERT INTO countries (name) VALUES (:name)";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            'name'     => $name
        ]);
    }

    public function getAll(): array
    {
        $query = "SELECT * FROM countries ORDER BY name ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $countries ?: null;
    }

    public function getById(int $id): ?array
    {
        $query = "SELECT * FROM countries WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);

        $country = $stmt->fetch(PDO::FETCH_ASSOC);
        return $country ?: null;
    }

    public function exists(string $name): bool
    {
        $query = "SELECT COUNT(*) FROM countries WHERE name = :name";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['name' => $name]);

        return $stmt->fetchColumn() > 0;
    }
}
