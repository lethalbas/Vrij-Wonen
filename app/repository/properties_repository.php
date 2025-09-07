<?php

require_once __DIR__ . "/base_repository.php";

class PropertiesRepository extends BaseRepository {
    protected $table = 'properties';
    protected $primaryKey = 'id';

    public function findByObjectId(int $objectId): array {
        $sql = "SELECT p.id, p.propertie 
                FROM properties p
                INNER JOIN connectprop cp ON p.id = cp.propertieid
                WHERE cp.objectid = ?
                ORDER BY p.propertie";
        $sth = $this->db->prepare($sql);
        $sth->execute([$objectId]);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByName(string $name): ?array {
        $sth = $this->db->prepare("SELECT * FROM properties WHERE propertie = ?");
        $sth->execute([$name]);
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function searchByName(string $searchTerm): array {
        $sth = $this->db->prepare("SELECT * FROM properties WHERE propertie LIKE ? ORDER BY propertie");
        $sth->execute(["%{$searchTerm}%"]);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPropertiesWithObjectCount(): array {
        $sql = "SELECT p.*, COUNT(cp.objectid) as object_count
                FROM properties p
                LEFT JOIN connectprop cp ON p.id = cp.propertieid
                GROUP BY p.id
                ORDER BY p.propertie";
        $sth = $this->db->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function connectToObject(int $objectId, array $propertyIds): bool {
        return $this->executeTransaction(function() use ($objectId, $propertyIds) {
            // First remove existing connections
            $sth = $this->db->prepare("DELETE FROM connectprop WHERE objectid = ?");
            if (!$sth->execute([$objectId])) {
                return false;
            }

            // Add new connections
            if (!empty($propertyIds)) {
                $sth = $this->db->prepare("INSERT INTO connectprop (objectid, propertieid) VALUES (?, ?)");
                foreach ($propertyIds as $propertyId) {
                    if (!$sth->execute([$objectId, $propertyId])) {
                        return false;
                    }
                }
            }

            return true;
        });
    }
}
