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

    // create object
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

    // update object
    function update($id, $data) {
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
        $fhu = new file_handler_util();
        foreach($old_images as $key => $image){
            $newimage = array_key_exists($key, $data["images"]);
            if($newimage){
                $imgname = $fhu->upload($data["images"][$key]);
                if($imgname == "error"){
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
            else{
                switch ($key){
                    case "1":
                        $formatted_object["mainimage"] = $old_images["1"];
                        unset($old_images["1"]);
                        break;
                    case "2":
                        $formatted_object["image2"] = $old_images["2"];
                        unset($old_images["2"]);
                        break;
                    case "3":
                        $formatted_object["image3"] = $old_images["3"];
                        unset($old_images["3"]);
                        break;
                    case "4":
                        $formatted_object["image4"] = $old_images["4"];
                        unset($old_images["4"]);
                        break;
                    case "5":
                        $formatted_object["image5"] = $old_images["5"];
                        unset($old_images["5"]);
                        break;
                }
            }
        }
        $formatted_data = array(
            "object" => $formatted_object,
            "properties" => $data["properties"]
        );
        if($this->model->update($id, $formatted_data)){
            foreach($old_images as $image){
                if($image != ""){
                    $fhu->delete($image);
                }
            }
        }
        else{
            $lu->create_custom_log("Possible data inconsistency: object was not updated but there were images uploaded to the user images folder!");
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