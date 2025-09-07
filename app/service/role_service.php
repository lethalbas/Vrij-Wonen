<?php

require_once __DIR__ . "/../util/db_connection_util.php";

class RoleService {
    
    private $db;
    
    public function __construct() {
        $dbUtil = new db_connection_util();
        $this->db = $dbUtil->get_db();
    }
    
    public function getUserRoles(int $userId): array {
        $sth = $this->db->prepare("
            SELECT r.id, r.name, r.description, r.priority, ur.assigned_at
            FROM roles r
            INNER JOIN user_roles ur ON r.id = ur.role_id
            WHERE ur.user_id = ?
            ORDER BY r.priority ASC
        ");
        $sth->execute([$userId]);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUserRolesByPriority(int $userId, int $maxPriority): array {
        $sth = $this->db->prepare("
            SELECT r.id, r.name, r.description, r.priority
            FROM roles r
            INNER JOIN user_roles ur ON r.id = ur.role_id
            WHERE ur.user_id = ? AND r.priority <= ?
            ORDER BY r.priority ASC
        ");
        $sth->execute([$userId, $maxPriority]);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function hasRole(int $userId, string $roleName): bool {
        $sth = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM user_roles ur
            INNER JOIN roles r ON ur.role_id = r.id
            WHERE ur.user_id = ? AND r.name = ?
        ");
        $sth->execute([$userId, $roleName]);
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
    
    public function hasRoleOrHigher(int $userId, string $roleName): bool {
        $sth = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM user_roles ur
            INNER JOIN roles r ON ur.role_id = r.id
            INNER JOIN roles target_role ON target_role.name = ?
            WHERE ur.user_id = ? AND r.priority <= target_role.priority
        ");
        $sth->execute([$roleName, $userId]);
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
    
    public function assignRole(int $userId, int $roleId, ?int $assignedBy = null): bool {
        // First, remove archived role if assigning a non-archived role
        $role = $this->getRoleById($roleId);
        if ($role && $role['name'] !== 'archived') {
            $archivedRole = $this->getRoleByName('archived');
            if ($archivedRole) {
                $this->removeRoleInternal($userId, $archivedRole['id']);
            }
        }
        
        $sth = $this->db->prepare("
            INSERT INTO user_roles (user_id, role_id, assigned_by)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE assigned_at = CURRENT_TIMESTAMP
        ");
        return $sth->execute([$userId, $roleId, $assignedBy]);
    }
    
    private function removeRoleInternal(int $userId, int $roleId): bool {
        $sth = $this->db->prepare("DELETE FROM user_roles WHERE user_id = ? AND role_id = ?");
        return $sth->execute([$userId, $roleId]);
    }
    
    public function removeRole(int $userId, int $roleId): bool {
        $sth = $this->db->prepare("DELETE FROM user_roles WHERE user_id = ? AND role_id = ?");
        $result = $sth->execute([$userId, $roleId]);
        
        if ($result) {
            // Check if user has any roles left
            $remainingRoles = $this->getUserRoles($userId);
            
            // If no roles left, assign archived role
            if (empty($remainingRoles)) {
                $archivedRole = $this->getRoleByName('archived');
                if ($archivedRole) {
                    $this->assignRole($userId, $archivedRole['id'], null);
                }
            }
        }
        
        return $result;
    }
    
    public function getAllRoles(): array {
        $sth = $this->db->prepare("SELECT * FROM roles ORDER BY priority ASC");
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getRoleByName(string $roleName): ?array {
        $sth = $this->db->prepare("SELECT * FROM roles WHERE name = ? LIMIT 1");
        $sth->execute([$roleName]);
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    public function getRoleById(int $roleId): ?array {
        $sth = $this->db->prepare("SELECT * FROM roles WHERE id = ? LIMIT 1");
        $sth->execute([$roleId]);
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    public function getUserHighestPriorityRole(int $userId): ?array {
        $sth = $this->db->prepare("
            SELECT r.*
            FROM roles r
            INNER JOIN user_roles ur ON r.id = ur.role_id
            WHERE ur.user_id = ?
            ORDER BY r.priority ASC
            LIMIT 1
        ");
        $sth->execute([$userId]);
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    public function removeAllRolesFromUser(int $userId): bool {
        $sth = $this->db->prepare("DELETE FROM user_roles WHERE user_id = ?");
        $result = $sth->execute([$userId]);
        
        if ($result) {
            // Assign archived role after removing all roles
            $archivedRole = $this->getRoleByName('archived');
            if ($archivedRole) {
                $this->assignRole($userId, $archivedRole['id'], null);
            }
        }
        
        return $result;
    }
}
