<?php

require_once "controller.php";
require_once __DIR__ . "/../repository/properties_repository.php";
require_once __DIR__ . "/../service/properties_service.php";

class properties_controller extends controller {

    private $service;

    function __construct() {
        $repository = new PropertiesRepository();
        $this->service = new PropertiesService($repository);
    }

    // get all properties for select box
    function get_all() {
        return $this->service->getAll();
    }

    // get all properties by a specific object id
    function get_by_object($id) {
        return $this->service->getByObjectId($id);
    }

    // get property by id (for API)
    function get_by_id($id) {
        return $this->service->getById($id);
    }

    // search properties by name
    function search_by_name($searchTerm) {
        return $this->service->searchByName($searchTerm);
    }

    // get properties with object count
    function get_properties_with_object_count() {
        return $this->service->getPropertiesWithObjectCount();
    }

    // connect properties to object
    function connect_to_object($objectId, $propertyIds) {
        return $this->service->connectToObject($objectId, $propertyIds);
    }

    // create property
    function create($data) {
        return $this->service->createProperty($data);
    }

    // update property
    function update($id, $data) {
        return $this->service->updateProperty($id, $data);
    }
}