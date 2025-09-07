<?php
require_once __DIR__ . "/../controller/staff_controller.php";
require_once __DIR__ . "/../service/role_service.php";

class user_login_session_util {

    // current_login_status value meanings:
    // 0 = Anonymous user
    // 1 = Medewerker
    // 2 = Administrator
    // 3 = System Administrator
    // 4 = API Administrator
    // 5 = Gearchiveerde medewerker (geen toegang tot beheersfuncties)
    private $current_login_status = 0;
    private $current_user_id = null;
    private $current_user_roles = [];

    // staff controller and role service
    private $sc;
    private $roleService;

    function __construct() {
        // define controllers
        $this->sc = new staff_controller();
        $this->roleService = new RoleService();

        // set the current login status based on roles
        $sess_info = $this->sc->get_by_session(session_id());
        if ($sess_info != ""){
            $this->current_user_id = $sess_info["id"];
            $this->current_user_roles = $this->roleService->getUserRoles($this->current_user_id);
            
            // Determine login status based on highest priority role
            $highestRole = $this->roleService->getUserHighestPriorityRole($this->current_user_id);
            if ($highestRole) {
                switch ($highestRole['name']) {
                    case 'api_admin':
                        $this->current_login_status = 4;
                        break;
                    case 'system_admin':
                        $this->current_login_status = 3;
                        break;
                    case 'admin':
                        $this->current_login_status = 2;
                        break;
                    case 'medewerker':
                        $this->current_login_status = 1;
                        break;
                    case 'archived':
                        $this->current_login_status = 5;
                        break;
                    default:
                        $this->current_login_status = 1;
                        break;
                }
            } else {
                // No roles found - user is archived or has no access
                $this->current_login_status = 5; // archived
            }
        }
        else{
            $this->current_login_status = 0;
        }
    }

    // return login status for restricting views to certain moderation levels
    function get_login_status(){
        return $this->current_login_status;
    }

    // log in and create session
    function login_user($username, $password){
        if($this->sc->log_in($username, $password)){
            session_create_id();
            $this->sc->set_session($username, session_id());
            return true;
        }
        return false;
    }

    // generates a new session id to log out
    function log_out() {
        session_regenerate_id();
    }
    
    // Check if user has specific role
    function has_role($roleName) {
        if ($this->current_user_id === null) {
            return false;
        }
        return $this->roleService->hasRole($this->current_user_id, $roleName);
    }
    
    // Check if user has role or higher priority role
    function has_role_or_higher($roleName) {
        if ($this->current_user_id === null) {
            return false;
        }
        return $this->roleService->hasRoleOrHigher($this->current_user_id, $roleName);
    }
    
    // Get all user roles
    function get_user_roles() {
        return $this->current_user_roles;
    }
    
    // Get current user ID
    function get_user_id() {
        return $this->current_user_id;
    }
    
    // Check if user is admin (backward compatibility)
    function is_admin() {
        return $this->has_role_or_higher('admin');
    }
    
    // Check if user is system admin
    function is_system_admin() {
        return $this->has_role('system_admin');
    }
    
    // Check if user is API admin
    function is_api_admin() {
        return $this->has_role('api_admin');
    }
    
    // Check if user is archived
    function is_archived() {
        return $this->has_role('archived');
    }
    
    // Check if user has access to management functions (not archived)
    function has_management_access() {
        return !$this->is_archived() && $this->get_login_status() > 0;
    }
}