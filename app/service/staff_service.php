<?php

require_once __DIR__ . "/base_service.php";

class StaffService extends BaseService {

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
            'sessionkey' => '',
            'admin' => isset($data['admin']) ? (int)$data['admin'] : 0
        ];

        return $this->repository->createStaff($staffData);
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
}
