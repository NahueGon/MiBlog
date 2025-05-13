<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Province extends Model
{
    public function create(string $name, int $countryId): bool
    {
        $query = "INSERT INTO provinces (name, country_id) VALUES (:name, :country_id)";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            'name'       => $name,
            'country_id' => $countryId
        ]);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM provinces WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $province = $stmt->fetch(PDO::FETCH_ASSOC);
        return $province ?: null;
    }

    public function getCountryNameFromProvince(int $provinceId): ?string
    {
        $query = "SELECT countries.name AS country_name
                FROM provinces
                JOIN countries ON provinces.country_id = countries.id
                WHERE provinces.id = :province_id";

        $stmt = $this->db->prepare($query);
        $stmt->execute(['province_id' => $provinceId]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['country_name'] ?? null;
    }

    public function findByCountryId(int $countryId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM provinces WHERE country_id = :country_id ORDER BY name ASC");
        $stmt->execute(['country_id' => $countryId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll(): array
    {
        $query = "SELECT * FROM provinces ORDER BY name ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $provinces = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $provinces ?: null;
    }
}
