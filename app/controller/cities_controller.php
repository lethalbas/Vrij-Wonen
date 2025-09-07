<?php

require_once "controller.php";
require_once __DIR__ . "/../model/cities_model.php";

class cities_controller extends controller {

    function __construct() {
        $this->model = new cities_model();
    }

    // get all cities
    function get_all() {
        return $this->model->get_all();
    }

    // get all cities that are currently in use by one or more objects
    function get_all_used() {
        return $this->model->get_all_used();
    }

    // get city by id
    function get_by_id($id) {
        return $this->model->get_by_id($id);
    }
}