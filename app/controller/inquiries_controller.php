<?php

require_once "controller.php";
require_once __DIR__ . "/../model/inquiries_model.php";

class inquiries_controller extends controller {

    function __construct() {
        $this->model = new inquiries_model();
    }

}