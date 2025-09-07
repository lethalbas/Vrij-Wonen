<?php

require_once "controller.php";
require_once __DIR__ . "/../util/file_handler_util.php";
require_once __DIR__ . "/../util/logging_util.php";
require_once __DIR__ . "/../model/objects_model.php";
require_once "properties_controller.php";

class objects_controller extends controller {

    function __construct() {
        $this->model = new objects_model();
    }

    // get all objects (with optional filters)
    function get_all($filters = NULL) {
        if($filters == NULL){
            return $this->model->get_all();
        }
        else{
            return $this->model->get_all_filtered($filters);
        }
    }

    // get all objects with filters (for API)
    function get_all_filtered($filters) {
        return $this->model->get_all_filtered($filters);
    }

    // get object by id (for API)
    function get_by_id($id) {
        return $this->model->get($id);
    }

    // create object
    function create($data) {
        $fhu = new file_handler_util();
        $formatted_object = array();
        $formatted_object = $data["object"];
        $formatted_object["sold"] = 0;
    
        // upload images
        $main_image = null;
        $image_2 = null;
        $image_3 = null;
        $image_4 = null;
        $image_5 = null;
        foreach($data["images"] as $key => $image){
            $img_name = $fhu->upload($image);
            switch ($key) {
                case "1":
                    $main_image = $img_name;
                    break;
                case "2":
                    $image_2 = $img_name;
                    break;
                case "3":
                    $image_3 = $img_name;
                    break;
                case "4":
                    $image_4 = $img_name;
                    break;
                case "5":
                    $image_5 = $img_name;
                    break;
            }
        }
    
        $formatted_object["mainimage"] = $main_image;
        $formatted_object["image2"] = $image_2;
        $formatted_object["image3"] = $image_3;
        $formatted_object["image4"] = $image_4;
        $formatted_object["image5"] = $image_5;

        $formatted_data = array(
            "object" => $formatted_object,
            "properties" => $data["properties"]
        );
    
        if ($this->model->create($formatted_data)) {
            return true;
        } else {
            // error inserting data, delete uploaded images
            $fhu->delete("$main_image");
            $fhu->delete("$image_2");
            $fhu->delete("$image_3");
            $fhu->delete("$image_4");
            $fhu->delete("$image_5");
            throw new Exception("Error: couldn't insert data");
        }
    }

    // update object
    function update($id, $data) {
        $fhu = new file_handler_util();
        $formatted_object = array();
        $old_object = $this->model->get($id);
        $old_images = array(
        "1" => $old_object["mainimage"],
        "2" => $old_object["image2"],
        "3" => $old_object["image3"],
        "4" => $old_object["image4"],
        "5" => $old_object["image5"]
        );
        $formatted_object = $data["object"];
        foreach($data["images"] as $key => $image) {
            $img_name = $fhu->upload($image);
            switch ($key) {
                case "1":
                    $formatted_object["mainimage"] = $img_name;
                    break;
                case "2":
                    $formatted_object["image2"] = $img_name;
                    break;
                case "3":
                    $formatted_object["image3"] = $img_name;
                    break;
                case "4":
                    $formatted_object["image4"] = $img_name;
                    break;
                case "5":
                    $formatted_object["image5"] = $img_name;
                    break;
            }
            if(array_key_exists($key, $old_images)){
                $fhu->delete($old_images[$key]);
                unset($old_images[$key]);
            }
        }
        foreach($old_images as $key => $value){
            switch ($key) {
                case "1":
                    if (!array_key_exists("1", $data["images"])) {
                        $formatted_object["mainimage"] = $old_images["1"];
                    }
                    break;
                case "2":
                    if (!array_key_exists("2", $data["images"])) {
                        $formatted_object["image2"] = $old_images["2"];
                    }
                    break;
                case "3":
                    if (!array_key_exists("3", $data["images"])) {
                        $formatted_object["image3"] = $old_images["3"];
                    }
                    break;
                case "4":
                    if (!array_key_exists("4", $data["images"])) {
                        $formatted_object["image4"] = $old_images["4"];
                    }
                    break;
                case "5":
                    if (!array_key_exists("5", $data["images"])) {
                        $formatted_object["image5"] = $old_images["5"];
                    }
                    break;
            }
        }
        $formatted_data = array(
            "object" => $formatted_object,
            "properties" => $data["properties"]
        );
        if ($this->model->update($id, $formatted_data)) {
            return true;
        } else {
            // error updating data, delete uploaded images
            $fhu->delete("$main_image");
            $fhu->delete("$image2");
            $fhu->delete("$image3");
            $fhu->delete("$image4");
            $fhu->delete("$image5");
            throw new Exception("Error: couldn't update data");
        }
    }

    // get object details
    function get($id) {
        $pc = new properties_controller();
        $object_data = $this->model->get($id);
        $properties = $pc->get_by_object($id);
        return array("object" => $object_data, "properties" => $properties);
    }

    // delete object
    function delete($id) {
        $fhu = new file_handler_util();
        $lu = new logging_util();
        $object = $this->model->get($id);
        if($this->model->delete($id)){
            try{
                // remove images
                $fhu->delete($object["mainimage"]);
                $fhu->delete($object["image2"]);
                $fhu->delete($object["image3"]);
                $fhu->delete($object["image4"]);
                $fhu->delete($object["image5"]);
                return true;
            }
            catch (Exception $e) {
                // couldn't remove object images
                $lu->create_custom_log("Possible data inconsistency: object was deleted but there was an error trying to delete all linked image files!");
                return true;
            }
        }
        else{
            // couldnt delete data
            throw new Exception("Error: couldn't update data");
        }
    }

}