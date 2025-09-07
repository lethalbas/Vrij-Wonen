<?php

require_once __DIR__ . "/../util/db_connection_util.php";
require_once __DIR__ . "/../service/role_service.php";

class api_auth_util {
    
    private $db;
    private $roleService;
    
    public function __construct() {
        $dbUtil = new db_connection_util();
        $this->db = $dbUtil->get_db();
        $this->roleService = new RoleService();
    }
    
    /**
     * Authenticate API request using Basic Auth
     * Returns user data if successful, null if failed
     */
    public function authenticateRequest(): ?array {
        // Check for Authorization header in multiple ways
        $auth_header = null;
        
        // Try $_SERVER first
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $auth_header = $_SERVER['HTTP_AUTHORIZATION'];
        } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $auth_header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        } elseif (isset($_SERVER['Authorization'])) {
            $auth_header = $_SERVER['Authorization'];
        } else {
            // Try getallheaders() as fallback
            $headers = getallheaders();
            if (isset($headers['Authorization'])) {
                $auth_header = $headers['Authorization'];
            } elseif (isset($headers['authorization'])) {
                $auth_header = $headers['authorization'];
            }
        }
        
        if (!$auth_header) {
            return null;
        }
        
        // Check if it's Basic Auth
        if (strpos($auth_header, 'Basic ') !== 0) {
            return null;
        }
        
        // Decode credentials
        $credentials = base64_decode(substr($auth_header, 6));
        if ($credentials === false) {
            return null;
        }
        
        list($username, $password) = explode(':', $credentials, 2);
        
        // Validate credentials
        return $this->validateCredentials($username, $password);
    }
    
    /**
     * Validate username and password (public method for API login endpoint)
     */
    public function validateCredentials(string $username, string $password): ?array {
        $sth = $this->db->prepare("
            SELECT id, username, email, passwordhash 
            FROM staff 
            WHERE username = ? 
            LIMIT 1
        ");
        $sth->execute([$username]);
        $user = $sth->fetch(PDO::FETCH_ASSOC);
        
        if (!$user || !password_verify($password, $user['passwordhash'])) {
            return null;
        }
        
        // Get user roles
        $user['roles'] = $this->roleService->getUserRoles($user['id']);
        
        return $user;
    }
    
    /**
     * Check if user has required role for API access
     */
    public function hasApiAccess(array $user): bool {
        // Check if user has api_admin role or higher privilege
        return $this->roleService->hasRoleOrHigher($user['id'], 'api_admin');
    }
    
    /**
     * Check if user has specific role
     */
    public function hasRole(array $user, string $roleName): bool {
        return $this->roleService->hasRole($user['id'], $roleName);
    }
    
    /**
     * Check if user has role or higher privilege
     */
    public function hasRoleOrHigher(array $user, string $roleName): bool {
        return $this->roleService->hasRoleOrHigher($user['id'], $roleName);
    }
    
    /**
     * Send authentication required response
     */
    public function sendAuthRequired(): void {
        header('WWW-Authenticate: Basic realm="Vrij Wonen API"');
        http_response_code(401);
        echo json_encode(['error' => 'Authentication required']);
        exit();
    }
    
    /**
     * Send forbidden response
     */
    public function sendForbidden(): void {
        http_response_code(403);
        echo json_encode(['error' => 'Insufficient permissions']);
        exit();
    }
    
    /**
     * Get current authenticated user
     */
    public function getCurrentUser(): ?array {
        return $this->authenticateRequest();
    }
}
