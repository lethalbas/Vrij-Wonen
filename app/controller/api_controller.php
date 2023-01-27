<?php

require_once "controller.php";
require_once __DIR__ . "/../model/api_model.php";

class api_controller extends controller {

    function __construct() {
        $this->model = new api_model();
    }

}