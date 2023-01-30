<?php

require_once "model.php";

class cities_model extends model {

    // get all cities
    function get_all() {
        $sth = $this->db->prepare("SELECT * from cities;");
        $sth->execute();
        return $sth->fetchAll();
    }

    // get all cities that are currently in use by one or more objects
    function get_all_used() {
        $sth = $this->db->prepare("SELECT cities.* 
        from objects
        LEFT JOIN cities ON objects.cityid = cities.id
        GROUP BY cities.id;");
        $sth->execute();
        return $sth->fetchAll();
    }

}