<?php

require_once "controller.php";
require_once __DIR__ . "/../model/properties_model.php";

class properties_controller extends controller {

    function __construct() {
        $this->model = new properties_model();
    }

}