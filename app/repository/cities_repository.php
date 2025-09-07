<?php

require_once __DIR__ . "/base_repository.php";

class CitiesRepository extends BaseRepository {
    protected $table = 'cities';
    protected $primaryKey = 'id';

    public function findAllUsed(): array {
        $sql = "SELECT DISTINCT c.* 
                FROM cities c
                INNER JOIN objects o ON c.id = o.cityid
                ORDER BY c.citiename";
        $sth = $this->db->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByName(string $name): ?array {
        $sth = $this->db->prepare("SELECT * FROM cities WHERE citiename = ?");
        $sth->execute([$name]);
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function searchByName(string $searchTerm): array {
        $sth = $this->db->prepare("SELECT * FROM cities WHERE citiename LIKE ? ORDER BY citiename");
        $sth->execute(["%{$searchTerm}%"]);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCitiesWithObjectCount(): array {
        $sql = "SELECT c.*, COUNT(o.id) as object_count
                FROM cities c
                LEFT JOIN objects o ON c.id = o.cityid
                GROUP BY c.id
                ORDER BY c.citiename";
        $sth = $this->db->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
}
