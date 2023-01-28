<?php

require_once "controller.php";
require_once __DIR__ . "/../util/file_handler_util.php";
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

    function create($data) {
        $formatted_object = array();
        $formatted_object = $data["object"];
        $formatted_object["sold"] = 0;
        $fhu = new file_handler_util();
        foreach($data["images"] as $key => $image){
            $imgname = $fhu->upload($image);
            switch ($key){
                case "1":
                    $formatted_object["mainimage"] = $imgname;
                    break;
                case "2":
                    $formatted_object["image2"] = $imgname;
                    break;
                case "3":
                    $formatted_object["image3"] = $imgname;
                    break;
                case "4":
                    $formatted_object["image4"] = $imgname;
                    break;
                case "5":
                    $formatted_object["image5"] = $imgname;
                    break;
            }
        }
        $formatted_data = array(
            "object" => $formatted_object,
            "properties" => $data["properties"]
        );
        $this->model->create($formatted_data);
    }

}