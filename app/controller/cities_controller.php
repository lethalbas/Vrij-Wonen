<?php

require_once "controller.php";
require_once __DIR__ . "/../repository/cities_repository.php";
require_once __DIR__ . "/../service/cities_service.php";

class cities_controller extends controller {

    private $service;

    function __construct() {
        $repository = new CitiesRepository();
        $this->service = new CitiesService($repository);
    }

    // get all cities
    function get_all() {
        return $this->service->getAll();
    }

    // get all cities that are currently in use by one or more objects
    function get_all_used() {
        return $this->service->getAllUsed();
    }

    // get city by id
    function get_by_id($id) {
        return $this->service->getById($id);
    }

    // search cities by name
    function search_by_name($searchTerm) {
        return $this->service->searchByName($searchTerm);
    }

    // get cities with object count
    function get_cities_with_object_count() {
        return $this->service->getCitiesWithObjectCount();
    }

    // create city
    function create($data) {
        return $this->service->createCity($data);
    }

    // update city
    function update($id, $data) {
        return $this->service->updateCity($id, $data);
    }

    // delete city
    function delete($id) {
        return $this->service->deleteCity($id);
    }
}