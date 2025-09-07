<?php

require_once "controller.php";
require_once __DIR__ . "/../repository/staff_repository.php";
require_once __DIR__ . "/../service/staff_service.php";

class staff_controller extends controller {

    private $service;

    function __construct() {
        $repository = new StaffRepository();
        $this->service = new StaffService($repository);
    }

    // get all staff members
    function get_all() {
        return $this->service->getAll();
    }

    // get by session key 
    function get_by_session($key) {
        return $this->service->findBySession($key);
    }

    // set session key in db
    function set_session($user, $session){
        return $this->service->updateSession($user, $session);
    }

    // log in check
    function log_in($user, $pass){
        return $this->service->loginUser($user, $pass);
    }

    // delete staff member
    function delete($id){
        return $this->service->deleteNonAdmin($id);
    }
    
    // create staff member
    function create($data){
        return $this->service->createStaff($data);
    }

    // get staff member by id (for API)
    function get_by_id($id) {
        return $this->service->getById($id);
    }

    // get admins only
    function get_admins() {
        return $this->service->getAdmins();
    }

    // get non-admins only
    function get_non_admins() {
        return $this->service->getNonAdmins();
    }

    // update staff member
    function update($id, $data) {
        return $this->service->updateStaff($id, $data);
    }

    // update password
    function update_password($id, $password) {
        return $this->service->updatePassword($id, password_hash($password, PASSWORD_DEFAULT));
    }

    // logout user
    function logout($username) {
        return $this->service->logoutUser($username);
    }
    
    // Role management methods
    function assign_role($userId, $roleId, $assignedBy = null) {
        return $this->service->assignRoleToUser($userId, $roleId, $assignedBy);
    }
    
    function remove_role($userId, $roleId) {
        return $this->service->removeRoleFromUser($userId, $roleId);
    }
    
    function get_user_roles($userId) {
        return $this->service->getUserRoles($userId);
    }
    
    function has_role($userId, $roleName) {
        return $this->service->hasRole($userId, $roleName);
    }
    
    function has_role_or_higher($userId, $roleName) {
        return $this->service->hasRoleOrHigher($userId, $roleName);
    }
    
    function get_all_roles() {
        return $this->service->getAllRoles();
    }
    
    // Role management with access control
    function get_manageable_users($currentUserId) {
        return $this->service->getManageableUsers($currentUserId);
    }
    
    function get_assignable_roles($currentUserId) {
        return $this->service->getAssignableRoles($currentUserId);
    }
    
    function can_manage_user($currentUserId, $targetUserId) {
        return $this->service->canManageUser($currentUserId, $targetUserId);
    }
    
    function can_assign_role($currentUserId, $roleId) {
        return $this->service->canAssignRole($currentUserId, $roleId);
    }
    
    // Archive management methods
    function archive_user($userId, $archivedBy) {
        return $this->service->archiveUser($userId, $archivedBy);
    }
    
    function unarchive_user($userId, $unarchivedBy) {
        return $this->service->unarchiveUser($userId, $unarchivedBy);
    }
    
    function ensure_user_has_role($userId) {
        return $this->service->ensureUserHasRole($userId);
    }
    
    function can_archive_user($currentUserId, $targetUserId) {
        return $this->service->canArchiveUser($currentUserId, $targetUserId);
    }
}