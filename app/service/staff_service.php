<?php

require_once __DIR__ . "/base_service.php";
require_once __DIR__ . "/role_service.php";

class StaffService extends BaseService {

    private $roleService;

    public function __construct($repository) {
        parent::__construct($repository);
        $this->roleService = new RoleService();
    }

    public function findBySession(string $sessionKey): ?array {
        return $this->repository->findBySession($sessionKey);
    }

    public function findByUsername(string $username): ?array {
        return $this->repository->findByUsername($username);
    }

    public function findByEmail(string $email): ?array {
        return $this->repository->findByEmail($email);
    }

    public function updateSession(string $username, string $sessionKey): bool {
        return $this->repository->updateSession($username, $sessionKey);
    }

    public function clearSession(string $username): bool {
        return $this->repository->clearSession($username);
    }

    public function deleteNonAdmin(int $id): bool {
        return $this->repository->deleteNonAdmin($id);
    }

    public function getAdmins(): array {
        return $this->repository->getAdmins();
    }

    public function getNonAdmins(): array {
        return $this->repository->getNonAdmins();
    }

    public function updatePassword(int $id, string $passwordHash): bool {
        return $this->repository->updatePassword($id, $passwordHash);
    }

    public function updateAdminStatus(int $id, bool $isAdmin): bool {
        return $this->repository->updateAdminStatus($id, $isAdmin);
    }

    public function validateStaffData(array $data): array {
        $errors = [];

        // Username validation
        if (empty($data['username'])) {
            $errors[] = 'Username is required';
        } elseif (strlen($data['username']) < 3) {
            $errors[] = 'Username must be at least 3 characters';
        } elseif (strlen($data['username']) > 50) {
            $errors[] = 'Username must be less than 50 characters';
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $data['username'])) {
            $errors[] = 'Username can only contain letters, numbers and underscores';
        }

        // Email validation
        if (empty($data['email'])) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }

        // Password validation (only for new staff or password updates)
        if (isset($data['password']) && !empty($data['password'])) {
            if (strlen($data['password']) < 6) {
                $errors[] = 'Password must be at least 6 characters';
            }
        }

        // Check for existing username
        if (!empty($data['username'])) {
            $existing = $this->repository->findByUsername($data['username']);
            if ($existing && (!isset($data['id']) || $existing['id'] != $data['id'])) {
                $errors[] = 'Username already exists';
            }
        }

        // Check for existing email
        if (!empty($data['email'])) {
            $existing = $this->repository->findByEmail($data['email']);
            if ($existing && (!isset($data['id']) || $existing['id'] != $data['id'])) {
                $errors[] = 'Email already exists';
            }
        }

        return $errors;
    }

    public function createStaff(array $data): bool {
        $errors = $this->validateStaffData($data);
        if (!empty($errors)) {
            throw new InvalidArgumentException('Validation failed: ' . implode(', ', $errors));
        }

        $staffData = [
            'username' => trim($data['username']),
            'email' => trim($data['email']),
            'passwordhash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'sessionkey' => ''
        ];

        $success = $this->repository->createStaff($staffData);
        
        // If staff creation successful and roles are specified, assign roles
        if ($success && isset($data['roles']) && is_array($data['roles'])) {
            $userId = $this->repository->getLastInsertId();
            foreach ($data['roles'] as $roleId) {
                $this->roleService->assignRole($userId, $roleId);
            }
        }
        
        return $success;
    }

    public function updateStaff(int $id, array $data): bool {
        $errors = $this->validateStaffData($data);
        if (!empty($errors)) {
            throw new InvalidArgumentException('Validation failed: ' . implode(', ', $errors));
        }

        $updateData = [
            'username' => trim($data['username']),
            'email' => trim($data['email'])
        ];

        // Update password if provided
        if (isset($data['password']) && !empty($data['password'])) {
            $updateData['passwordhash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // Update admin status if provided
        if (isset($data['admin'])) {
            $updateData['admin'] = (int)$data['admin'];
        }

        return $this->repository->update($id, $updateData);
    }

    public function authenticateUser(string $username, string $password): ?array {
        $user = $this->repository->findByUsername($username);
        
        if (!$user || !password_verify($password, $user['passwordhash'])) {
            return null;
        }

        return $user;
    }

    public function generateSessionKey(): string {
        return bin2hex(random_bytes(32));
    }

    public function loginUser(string $username, string $password): bool {
        $user = $this->authenticateUser($username, $password);
        
        if (!$user) {
            return false;
        }

        $sessionKey = $this->generateSessionKey();
        return $this->repository->updateSession($username, $sessionKey);
    }

    public function logoutUser(string $username): bool {
        return $this->repository->clearSession($username);
    }
    
    // Role management methods
    public function assignRoleToUser(int $userId, int $roleId, ?int $assignedBy = null): bool {
        return $this->roleService->assignRole($userId, $roleId, $assignedBy);
    }
    
    public function removeRoleFromUser(int $userId, int $roleId): bool {
        return $this->roleService->removeRole($userId, $roleId);
    }
    
    public function getUserRoles(int $userId): array {
        return $this->roleService->getUserRoles($userId);
    }
    
    public function hasRole(int $userId, string $roleName): bool {
        return $this->roleService->hasRole($userId, $roleName);
    }
    
    public function hasRoleOrHigher(int $userId, string $roleName): bool {
        return $this->roleService->hasRoleOrHigher($userId, $roleName);
    }
    
    public function getAllRoles(): array {
        return $this->roleService->getAllRoles();
    }
    
    // Role management with access control
    public function getManageableUsers(int $currentUserId): array {
        $currentUserRoles = $this->roleService->getUserRoles($currentUserId);
        $currentHighestRole = $this->roleService->getUserHighestPriorityRole($currentUserId);
        
        if (!$currentHighestRole) {
            return [];
        }
        
        $allUsers = $this->repository->getAll();
        $manageableUsers = [];
        
        foreach ($allUsers as $user) {
            if ($user['id'] == $currentUserId) {
                continue; // Skip self
            }
            
            $userHighestRole = $this->roleService->getUserHighestPriorityRole($user['id']);
            
            // If user has no roles, they can be managed
            if (!$userHighestRole) {
                $manageableUsers[] = $user;
                continue;
            }
            
            // Current user can manage users with same or lower priority roles
            // API admin (priority 1) can manage other API admins
            if ($userHighestRole['priority'] >= $currentHighestRole['priority']) {
                $manageableUsers[] = $user;
            }
        }
        
        return $manageableUsers;
    }
    
    public function getAssignableRoles(int $currentUserId): array {
        $currentHighestRole = $this->roleService->getUserHighestPriorityRole($currentUserId);
        
        if (!$currentHighestRole) {
            return [];
        }
        
        $allRoles = $this->roleService->getAllRoles();
        $assignableRoles = [];
        
        foreach ($allRoles as $role) {
            // Current user can assign roles with same or lower priority
            // API admin (priority 1) can assign API admin roles to others
            if ($role['priority'] >= $currentHighestRole['priority']) {
                $assignableRoles[] = $role;
            }
        }
        
        return $assignableRoles;
    }
    
    public function canManageUser(int $currentUserId, int $targetUserId): bool {
        $currentHighestRole = $this->roleService->getUserHighestPriorityRole($currentUserId);
        $targetHighestRole = $this->roleService->getUserHighestPriorityRole($targetUserId);
        
        if (!$currentHighestRole) {
            return false;
        }
        
        // Can't manage self
        if ($currentUserId == $targetUserId) {
            return false;
        }
        
        // If target has no roles, they can be managed
        if (!$targetHighestRole) {
            return true;
        }
        
        // Can manage users with same or lower priority roles
        // API admin (priority 1) can manage other API admins
        return $targetHighestRole['priority'] >= $currentHighestRole['priority'];
    }
    
    public function canAssignRole(int $currentUserId, int $roleId): bool {
        $currentHighestRole = $this->roleService->getUserHighestPriorityRole($currentUserId);
        $role = $this->roleService->getRoleById($roleId);
        
        if (!$currentHighestRole || !$role) {
            return false;
        }
        
        // Can assign roles with same or lower priority
        // API admin (priority 1) can assign API admin roles
        return $role['priority'] >= $currentHighestRole['priority'];
    }
    
    // Archive management methods
    public function archiveUser(int $userId, int $archivedBy): bool {
        // Get archived role
        $archivedRole = $this->roleService->getRoleByName('archived');
        if (!$archivedRole) {
            return false;
        }
        
        // Remove all existing roles
        $this->roleService->removeAllRolesFromUser($userId);
        
        // Assign archived role
        return $this->roleService->assignRole($userId, $archivedRole['id'], $archivedBy);
    }
    
    public function unarchiveUser(int $userId, int $unarchivedBy): bool {
        // Remove archived role
        $archivedRole = $this->roleService->getRoleByName('archived');
        if (!$archivedRole) {
            return false;
        }
        
        $result = $this->roleService->removeRole($userId, $archivedRole['id']);
        
        if ($result) {
            // Assign medewerker role by default
            $medewerkerRole = $this->roleService->getRoleByName('medewerker');
            if ($medewerkerRole) {
                $this->roleService->assignRole($userId, $medewerkerRole['id'], $unarchivedBy);
            }
        }
        
        return $result;
    }
    
    public function ensureUserHasRole(int $userId): bool {
        $userRoles = $this->roleService->getUserRoles($userId);
        
        // If user has no roles, assign archived role
        if (empty($userRoles)) {
            $archivedRole = $this->roleService->getRoleByName('archived');
            if ($archivedRole) {
                return $this->roleService->assignRole($userId, $archivedRole['id'], null);
            }
        }
        
        return true;
    }
    
    public function canArchiveUser(int $currentUserId, int $targetUserId): bool {
        // Can't archive self
        if ($currentUserId == $targetUserId) {
            return false;
        }
        
        // Use same logic as canManageUser
        return $this->canManageUser($currentUserId, $targetUserId);
    }
}
