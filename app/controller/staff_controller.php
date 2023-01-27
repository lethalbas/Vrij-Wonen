<?php

require_once "controller.php";
require_once __DIR__ . "/../model/staff_model.php";

class staff_controller extends controller {

    function __construct() {
        $this->model = new staff_model();
    }
    
}