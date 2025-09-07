<?php

require_once __DIR__ . "/base_repository.php";

class ObjectsRepository extends BaseRepository {
    protected $table = 'objects';
    protected $primaryKey = 'id';

    public function findAllWithCity(): array {
        $sql = "SELECT o.id, o.title, o.price, o.adress, c.citiename, o.mainimage
                FROM objects o
                INNER JOIN cities c ON o.cityid = c.id";
        $sth = $this->db->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAllFiltered(array $filters): array {
        $whereClause = [];
        $values = [];

        // Handle city filter
        if (isset($filters['cityid']) && !empty($filters['cityid'])) {
            if (is_array($filters['cityid'])) {
                $placeholders = str_repeat('?,', count($filters['cityid']) - 1) . '?';
                $whereClause[] = "o.cityid IN ({$placeholders})";
                $values = array_merge($values, $filters['cityid']);
            } else {
                $whereClause[] = "o.cityid = ?";
                $values[] = $filters['cityid'];
            }
        }

        // Handle property filters
        if (isset($filters['properties']) && !empty($filters['properties'])) {
            $placeholders = str_repeat('?,', count($filters['properties']) - 1) . '?';
            $whereClause[] = "cp.propertieid IN ({$placeholders})";
            $values = array_merge($values, $filters['properties']);
        }

        // Handle sold status
        if (isset($filters['sold'])) {
            $whereClause[] = "o.sold = ?";
            $values[] = $filters['sold'] ? 1 : 0;
        }

        $sql = "SELECT DISTINCT o.id, o.title, o.price, o.adress, c.citiename, o.mainimage
                FROM objects o
                INNER JOIN cities c ON o.cityid = c.id
                LEFT JOIN connectprop cp ON o.id = cp.objectid";

        if (!empty($whereClause)) {
            $sql .= " WHERE " . implode(' AND ', $whereClause);
        }

        $sql .= " GROUP BY o.id";

        $sth = $this->db->prepare($sql);
        $sth->execute($values);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByIdWithCity(int $id): ?array {
        $sql = "SELECT o.*, c.citiename
                FROM objects o
                INNER JOIN cities c ON o.cityid = c.id
                WHERE o.id = ?";
        $sth = $this->db->prepare($sql);
        $sth->execute([$id]);
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function createWithProperties(array $objectData, array $properties): bool {
        return $this->executeTransaction(function() use ($objectData, $properties) {
            // Insert object
            $fields = array_keys($objectData);
            $placeholders = str_repeat('?,', count($fields) - 1) . '?';
            $sql = "INSERT INTO objects (" . implode(',', $fields) . ") VALUES ({$placeholders})";
            $sth = $this->db->prepare($sql);
            
            if (!$sth->execute(array_values($objectData))) {
                return false;
            }

            $objectId = $this->db->lastInsertId();

            // Insert property connections
            if (!empty($properties)) {
                $sql = "INSERT INTO connectprop (objectid, propertieid) VALUES (?, ?)";
                $sth = $this->db->prepare($sql);
                
                foreach ($properties as $propertyId) {
                    if (!$sth->execute([$objectId, $propertyId])) {
                        return false;
                    }
                }
            }

            return true;
        });
    }

    public function updateWithProperties(int $id, array $objectData, array $properties): bool {
        return $this->executeTransaction(function() use ($id, $objectData, $properties) {
            // Update object
            $fields = array_keys($objectData);
            $setClause = implode(' = ?, ', $fields) . ' = ?';
            $sql = "UPDATE objects SET {$setClause} WHERE id = ?";
            $sth = $this->db->prepare($sql);
            
            $values = array_values($objectData);
            $values[] = $id;
            
            if (!$sth->execute($values)) {
                return false;
            }

            // Delete existing property connections
            $sth = $this->db->prepare("DELETE FROM connectprop WHERE objectid = ?");
            if (!$sth->execute([$id])) {
                return false;
            }

            // Insert new property connections
            if (!empty($properties)) {
                $sql = "INSERT INTO connectprop (objectid, propertieid) VALUES (?, ?)";
                $sth = $this->db->prepare($sql);
                
                foreach ($properties as $propertyId) {
                    if (!$sth->execute([$id, $propertyId])) {
                        return false;
                    }
                }
            }

            return true;
        });
    }

    public function deleteWithRelations(int $id): bool {
        return $this->executeTransaction(function() use ($id) {
            // Delete property connections
            $sth = $this->db->prepare("DELETE FROM connectprop WHERE objectid = ?");
            if (!$sth->execute([$id])) {
                return false;
            }

            // Delete inquiries
            $sth = $this->db->prepare("DELETE FROM inquiries WHERE objectid = ?");
            if (!$sth->execute([$id])) {
                return false;
            }

            // Delete object
            $sth = $this->db->prepare("DELETE FROM objects WHERE id = ?");
            return $sth->execute([$id]);
        });
    }

    public function getPropertiesByObjectId(int $objectId): array {
        $sql = "SELECT p.* FROM properties p
                INNER JOIN connectprop cp ON p.id = cp.propertieid
                WHERE cp.objectid = ?";
        $sth = $this->db->prepare($sql);
        $sth->execute([$objectId]);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
}
