<?php
require_once __DIR__ . "/../controller/staff_controller.php";

class user_login_session_util {

    // current_login_status value meanings:
    // 0 = Anonymous user
    // 1 = Moderator
    // 2 = Administrator
    private $current_login_status = 0;

    // staff controller
    private $sc;

    function __construct() {
        // define staff controller variable
        $this->sc = new staff_controller();

        // set the current login status
        $sess_info = $this->sc->get_by_session(session_id());
        if ($sess_info != ""){
            if ($sess_info["admin"] == 1){
                $this->current_login_status = 2;
            }
            else{
                $this->current_login_status = 1;
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

    // starts a new session id to log out
    function log_out() {
        session_regenerate_id();
    }
}