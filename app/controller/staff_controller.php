<?php

require_once "controller.php";
require_once __DIR__ . "/../model/staff_model.php";

class staff_controller extends controller {

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
        // TODO: use real random salt
        $temporary_salt = "salt";
        $hashed = crypt($pass, $temporary_salt);
        $data = $this->model->get_by_user($user);
        if($data["passwordhash"] == $hashed){
            return true;
        }
        else{
            return false;
        }
    }
    
}