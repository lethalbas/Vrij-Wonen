<?php

require_once "controller.php";
require_once __DIR__ . "/../model/inquiries_model.php";

class inquiries_controller extends controller {

    function __construct() {
        $this->model = new inquiries_model();
    }

    // get all inquiries
    function get_all() {
        return $this->model->get_all();
    }

    // complete inquiry
    function complete($id){
        $this->model->complete_inquiry($id);
    }

    // create inquiry
    function create($data){
        return $this->model->create($data);
    }

}