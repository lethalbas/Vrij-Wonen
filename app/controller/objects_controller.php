<?php

require_once "controller.php";
require_once __DIR__ . "/../model/objects_model.php";

class objects_controller extends controller {

    function __construct() {
        $this->model = new objects_model();
    }

    function get_all($filters = NULL) {
        if($filters == NULL){
            return $this->model->get_all();
        }
        else{
            return $this->model->get_all_filtered($filters);
        }
    }

}