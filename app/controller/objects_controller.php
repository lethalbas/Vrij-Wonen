<?php

require_once "controller.php";
require_once __DIR__ . "/../repository/objects_repository.php";
require_once __DIR__ . "/../service/objects_service.php";
require_once __DIR__ . "/../controller/properties_controller.php";

class objects_controller extends controller {

    private $service;

    function __construct() {
        $repository = new ObjectsRepository();
        $this->service = new ObjectsService($repository);
    }

    // get all objects (with optional filters)
    function get_all($filters = NULL) {
        if($filters == NULL){
            return $this->service->getAllWithCity();
        }
        else{
            return $this->service->getAllFiltered($filters);
        }
    }

    // get all objects with filters (for API)
    function get_all_filtered($filters) {
        return $this->service->getAllFiltered($filters);
    }

    // get object by id (for API)
    function get_by_id($id) {
        return $this->service->getByIdWithCity($id);
    }

    // create object
    function create($data) {
        return $this->service->createWithImages($data);
    }

    // update object
    function update($id, $data) {
        return $this->service->updateWithImages($id, $data);
    }

    // get object details
    function get($id) {
        return $this->service->getByIdWithProperties($id);
    }

    // delete object
    function delete($id) {
        return $this->service->deleteWithCleanup($id);
    }

}