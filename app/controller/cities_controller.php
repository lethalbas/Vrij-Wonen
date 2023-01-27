<?php

require_once "controller.php";
require_once __DIR__ . "/../model/cities_model.php";

class cities_controller extends controller {

    function __construct() {
        $this->model = new cities_model();
    }

}