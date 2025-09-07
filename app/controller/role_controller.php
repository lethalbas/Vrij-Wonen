<?php

require_once "controller.php";
require_once __DIR__ . "/../service/role_service.php";

class role_controller extends controller {

    private $roleService;

    function __construct() {
        $this->roleService = new RoleService();
    }

    // Get all roles
    function get_all() {
        return $this->roleService->getAllRoles();
    }

    // Get user roles
    function get_user_roles($userId) {
        return $this->roleService->getUserRoles($userId);
    }

    // Check if user has role
    function has_role($userId, $roleName) {
        return $this->roleService->hasRole($userId, $roleName);
    }

    // Check if user has role or higher
    function has_role_or_higher($userId, $roleName) {
        return $this->roleService->hasRoleOrHigher($userId, $roleName);
    }

    // Assign role to user
    function assign_role($userId, $roleId, $assignedBy = null) {
        return $this->roleService->assignRole($userId, $roleId, $assignedBy);
    }

    // Remove role from user
    function remove_role($userId, $roleId) {
        return $this->roleService->removeRole($userId, $roleId);
    }

    // Get role by name
    function get_role_by_name($roleName) {
        return $this->roleService->getRoleByName($roleName);
    }

    // Get user's highest priority role
    function get_user_highest_role($userId) {
        return $this->roleService->getUserHighestPriorityRole($userId);
    }
}
