<?php

require_once "controller.php";
require_once __DIR__ . "/../model/inquiries_model.php";

class inquiries_controller extends controller {

    function __construct() {
        $this->model = new inquiries_model();
    }

    function get_all() {
        return $this->model->get_all();
    }

    function complete($id){
        $this->model->complete_inquiry($id);
    }

}