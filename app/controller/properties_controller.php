<?php

require_once "controller.php";
require_once __DIR__ . "/../model/properties_model.php";

class properties_controller extends controller {

    function __construct() {
        $this->model = new properties_model();
    }

    // get all properties for select box
    function get_all() {
        return $this->model->get_all();
    }

    // get all properties by a specific object id
    function get_by_object($id) {
        $data = array("id" => $id);
        return $this->model->get_all_filtered($data);
    }

    // get property by id (for API)
    function get_by_id($id) {
        return $this->model->get_by_id($id);
    }

}