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
        if(isset($_SESSION["session_key"])){
            $sess_info = $this->sc->get_by_session($_SESSION["session_key"]);
            if (count($sess_info) > 0){
                if ($sess_info["admin"] == 1){
                    $this->current_login_status = 2;
                }
                else{
                    $this->current_login_status = 1;
                }
            }
        }
    }

    // return login status for restricting views to certain moderation levels
    function get_login_status(){
        return $this->current_login_status;
    }
}