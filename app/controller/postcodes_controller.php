<?php

require_once "controller.php";
require_once __DIR__ . "/../model/postcodes_model.php";

class postcodes_controller extends controller {

    function __construct() {
        $this->model = new postcodes_model();
    }

}