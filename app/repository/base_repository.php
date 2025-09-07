<?php

require_once __DIR__ . "/repository_interface.php";
require_once __DIR__ . "/../util/db_connection_util.php";

abstract class BaseRepository implements RepositoryInterface {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct() {
        $connection_util = new db_connection_util();
        $this->db = $connection_util->get_db();
    }

    public function findAll(): array {
        $sth = $this->db->prepare("SELECT * FROM {$this->table}");
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array {
        $sth = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?");
        $sth->execute([$id]);
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function findBy(array $criteria): array {
        if (empty($criteria)) {
            return $this->findAll();
        }

        $whereClause = [];
        $values = [];
        
        foreach ($criteria as $field => $value) {
            if (is_array($value)) {
                $placeholders = str_repeat('?,', count($value) - 1) . '?';
                $whereClause[] = "{$field} IN ({$placeholders})";
                $values = array_merge($values, $value);
            } else {
                $whereClause[] = "{$field} = ?";
                $values[] = $value;
            }
        }

        $sql = "SELECT * FROM {$this->table} WHERE " . implode(' AND ', $whereClause);
        $sth = $this->db->prepare($sql);
        $sth->execute($values);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool {
        $fields = array_keys($data);
        $placeholders = str_repeat('?,', count($fields) - 1) . '?';
        
        $sql = "INSERT INTO {$this->table} (" . implode(',', $fields) . ") VALUES ({$placeholders})";
        $sth = $this->db->prepare($sql);
        
        try {
            return $sth->execute(array_values($data));
        } catch (PDOException $e) {
            return false;
        }
    }

    public function update(int $id, array $data): bool {
        $fields = array_keys($data);
        $setClause = implode(' = ?, ', $fields) . ' = ?';
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = ?";
        $sth = $this->db->prepare($sql);
        
        $values = array_values($data);
        $values[] = $id;
        
        try {
            return $sth->execute($values);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete(int $id): bool {
        $sth = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        
        try {
            return $sth->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function count(): int {
        $sth = $this->db->prepare("SELECT COUNT(*) FROM {$this->table}");
        $sth->execute();
        return (int) $sth->fetchColumn();
    }

    protected function executeTransaction(callable $callback): bool {
        try {
            $this->db->beginTransaction();
            $result = $callback();
            $this->db->commit();
            return $result;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
