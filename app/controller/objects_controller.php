<?php

require_once __DIR__ . "/../model/objects_model.php";

class objects_controller {

    private $model;

    function __construct() {
        $this->model = new objects_model();
    }

    function get_all() {
        return $this->model->get_all();
    }

}