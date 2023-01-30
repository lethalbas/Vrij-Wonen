<?php

require_once "controller.php";
require_once __DIR__ . "/../util/file_handler_util.php";
require_once __DIR__ . "/../model/objects_model.php";
require_once "properties_controller.php";

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
            if($imgname == false){
                throw new Exception("Error: wrong image filetype");
            }
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
        if($this->model->create($formatted_data)){
            return true;
        }
        else{
            $fhu->delete($formatted_object["mainimage"]);
            $fhu->delete($formatted_object["image2"]);
            $fhu->delete($formatted_object["image3"]);
            $fhu->delete($formatted_object["image4"]);
            $fhu->delete($formatted_object["image5"]);
            throw new Exception("Error: couldn't insert data");
        }
    }

    function update($id, $data) {
        $formatted_object = array();
        $formatted_object = $data["object"];
        $fhu = new file_handler_util();
        foreach($data["images"] as $key => $image){
            $imgname = $fhu->upload($image);
            if($imgname == false){
                throw new Exception("Error: wrong image filetype");
            }
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
        $old_object = $this->model->get($id);
        $old_images = [$old_object["mainimage"], $old_object["image2"], $old_object["image3"], $old_object["image4"], $old_object["image5"]];
        $formatted_data = array(
            "object" => $formatted_object,
            "properties" => $data["properties"]
        );
        if($this->model->update($id, $formatted_data)){
            foreach($old_images as $image){
                $fhu->delete($image);
            }
        }
        else{
            $fhu->delete($formatted_object["mainimage"]);
            $fhu->delete($formatted_object["image2"]);
            $fhu->delete($formatted_object["image3"]);
            $fhu->delete($formatted_object["image4"]);
            $fhu->delete($formatted_object["image5"]);
            throw new Exception("Error: couldn't update data");
        }
    }

    function get($id) {
        $pc = new properties_controller();
        $object_data = $this->model->get($id);
        $properties = $pc->get_by_object($id);
        return array("object" => $object_data, "properties" => $properties);
    }

    function delete($id) {
        $fhu = new file_handler_util();
        $object = $this->model->get($id);
        if($this->model->delete($id)){
            $fhu->delete($object["mainimage"]);
            $fhu->delete($object["image2"]);
            $fhu->delete($object["image3"]);
            $fhu->delete($object["image4"]);
            $fhu->delete($object["image5"]);
            return true;
        }
        else{
            throw new Exception("Error: couldn't update data");
        }
    }

}