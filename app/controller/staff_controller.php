<?php

require_once "controller.php";
require_once __DIR__ . "/../model/staff_model.php";

class staff_controller extends controller {

    // TODO: use real random salt
    private $temporary_salt = "salt";

    function __construct() {
        $this->model = new staff_model();
    }

    function get_all() {
        return $this->model->get_all();
    }

    function get_by_session($key) {
        return $this->model->get_by_session($key);
    }

    function set_session($user, $session){
        $this->model->set_session($user, $session);
    }

    function log_in($user, $pass){
        $hashed = crypt($pass, $this->temporary_salt);
        $data = $this->model->get_by_user($user);
        if($data["passwordhash"] == $hashed){
            return true;
        }
        else{
            return false;
        }
    }

    function delete($id){
        $this->model->delete($id);
    }
    
    function create($data){
        $hashed = crypt($data["password"], $this->temporary_salt);
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
            return false;
        }
    }
}