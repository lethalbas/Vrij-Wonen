<?php

require_once "controller.php";
require_once __DIR__ . "/../model/properties_model.php";

class properties_controller extends controller {

    function __construct() {
        $this->model = new properties_model();
    }

    function get_all() {
        return $this->model->get_all();
    }

    function get_by_object($id) {
        $data = array("id" => $id);
        return $this->model->get_all_filtered($data);
    }

}