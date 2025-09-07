<?php

require_once __DIR__ . "/base_repository.php";

class InquiriesRepository extends BaseRepository {
    protected $table = 'inquiries';
    protected $primaryKey = 'id';

    public function findAllOrderedByHandled(): array {
        $sth = $this->db->prepare("SELECT * FROM inquiries ORDER BY handled");
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByObjectId(int $objectId): array {
        $sth = $this->db->prepare("SELECT * FROM inquiries WHERE objectid = ? ORDER BY id DESC");
        $sth->execute([$objectId]);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByEmail(string $email): array {
        $sth = $this->db->prepare("SELECT * FROM inquiries WHERE replyemail = ? ORDER BY id DESC");
        $sth->execute([$email]);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findUnhandled(): array {
        $sth = $this->db->prepare("SELECT * FROM inquiries WHERE handled = 0 ORDER BY id DESC");
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findHandled(): array {
        $sth = $this->db->prepare("SELECT * FROM inquiries WHERE handled = 1 ORDER BY id DESC");
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAsHandled(int $id): bool {
        $sth = $this->db->prepare("UPDATE inquiries SET handled = 1 WHERE id = ?");
        return $sth->execute([$id]);
    }

    public function markAsUnhandled(int $id): bool {
        $sth = $this->db->prepare("UPDATE inquiries SET handled = 0 WHERE id = ?");
        return $sth->execute([$id]);
    }

    public function getInquiriesWithObjectInfo(): array {
        $sql = "SELECT i.*, o.title as object_title, o.adress as object_address, c.citiename as city_name
                FROM inquiries i
                LEFT JOIN objects o ON i.objectid = o.id
                LEFT JOIN cities c ON o.cityid = c.id
                ORDER BY i.handled, i.id DESC";
        $sth = $this->db->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getInquiryWithObjectInfo(int $id): ?array {
        $sql = "SELECT i.*, o.title as object_title, o.adress as object_address, o.description as object_description,
                       c.citiename as city_name, o.price as object_price
                FROM inquiries i
                LEFT JOIN objects o ON i.objectid = o.id
                LEFT JOIN cities c ON o.cityid = c.id
                WHERE i.id = ?";
        $sth = $this->db->prepare($sql);
        $sth->execute([$id]);
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function createInquiry(array $data): bool {
        $sql = "INSERT INTO inquiries (objectid, fullname, replyemail, message) VALUES (?, ?, ?, ?)";
        $sth = $this->db->prepare($sql);
        
        try {
            return $sth->execute([
                $data['objectid'],
                $data['fullname'],
                $data['replyemail'],
                $data['message']
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getInquiryStats(): array {
        $sql = "SELECT 
                    COUNT(*) as total_inquiries,
                    SUM(CASE WHEN handled = 0 THEN 1 ELSE 0 END) as unhandled_inquiries,
                    SUM(CASE WHEN handled = 1 THEN 1 ELSE 0 END) as handled_inquiries,
                    COUNT(DISTINCT objectid) as unique_objects
                FROM inquiries";
        $sth = $this->db->prepare($sql);
        $sth->execute();
        return $sth->fetch(PDO::FETCH_ASSOC);
    }
}
