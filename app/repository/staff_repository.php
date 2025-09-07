<?php

require_once __DIR__ . "/base_repository.php";

class StaffRepository extends BaseRepository {
    protected $table = 'staff';
    protected $primaryKey = 'id';

    public function findBySession(string $sessionKey): ?array {
        $sth = $this->db->prepare("SELECT * FROM staff WHERE sessionkey = ? LIMIT 1");
        $sth->execute([$sessionKey]);
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function findByUsername(string $username): ?array {
        $sth = $this->db->prepare("SELECT id, username, email, passwordhash, admin FROM staff WHERE username = ? LIMIT 1");
        $sth->execute([$username]);
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function findByEmail(string $email): ?array {
        $sth = $this->db->prepare("SELECT * FROM staff WHERE email = ? LIMIT 1");
        $sth->execute([$email]);
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function updateSession(string $username, string $sessionKey): bool {
        $sth = $this->db->prepare("UPDATE staff SET sessionkey = ? WHERE username = ?");
        return $sth->execute([$sessionKey, $username]);
    }

    public function clearSession(string $username): bool {
        $sth = $this->db->prepare("UPDATE staff SET sessionkey = '' WHERE username = ?");
        return $sth->execute([$username]);
    }

    public function deleteNonAdmin(int $id): bool {
        $sth = $this->db->prepare("DELETE FROM staff WHERE id = ? AND admin = 0");
        return $sth->execute([$id]);
    }

    public function getAdmins(): array {
        $sth = $this->db->prepare("SELECT * FROM staff WHERE admin = 1 ORDER BY username");
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNonAdmins(): array {
        $sth = $this->db->prepare("SELECT * FROM staff WHERE admin = 0 ORDER BY username");
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createStaff(array $data): bool {
        $sql = "INSERT INTO staff (username, email, passwordhash, sessionkey, admin) VALUES (?, ?, ?, ?, ?)";
        $sth = $this->db->prepare($sql);
        
        try {
            return $sth->execute([
                $data['username'],
                $data['email'],
                $data['passwordhash'],
                $data['sessionkey'] ?? '',
                $data['admin'] ?? 0
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updatePassword(int $id, string $passwordHash): bool {
        $sth = $this->db->prepare("UPDATE staff SET passwordhash = ? WHERE id = ?");
        return $sth->execute([$passwordHash, $id]);
    }

    public function updateAdminStatus(int $id, bool $isAdmin): bool {
        $sth = $this->db->prepare("UPDATE staff SET admin = ? WHERE id = ?");
        return $sth->execute([$isAdmin ? 1 : 0, $id]);
    }
}
