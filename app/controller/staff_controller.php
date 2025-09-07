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
}