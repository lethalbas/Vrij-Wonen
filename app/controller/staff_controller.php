<?php

require_once "controller.php";
require_once __DIR__ . "/../model/staff_model.php";

class staff_controller extends controller {

    // TODO: use real random salt
    private $temporary_salt = "salt";

    function __construct() {
        $this->model = new staff_model();
    }

    // get all staff members
    function get_all() {
        return $this->model->get_all();
    }

    // get by session key 
    function get_by_session($key) {
        return $this->model->get_by_session($key);
    }

    // set session key in db
    function set_session($user, $session){
        $this->model->set_session($user, $session);
    }

    // log in check
    function log_in($user, $pass){
        $data = $this->model->get_by_user($user);
        if(password_verify($pass, $data["passwordhash"])){
            return true;
        }
        else{
            return false;
        }
    }

    // delete staff member
    function delete($id){
        $this->model->delete($id);
    }
    
    // create staff member
    function create($data){
        // hash password
        $hashed = password_hash($data["password"], PASSWORD_DEFAULT);
        $formatted_data = array(
            "username" => $data["username"],
            "email" => $data["email"],
            "passwordhash" => $hashed,
            "admin" => $data["admin"]
        );
        if($this->model->create($formatted_data)){
            return true;
        }
        else{
            // error inserting staff member
            throw new Exception("Error: couldn't insert data");
        }
    }
}