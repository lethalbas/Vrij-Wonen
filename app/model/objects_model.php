<?php

require_once "model.php";

class objects_model extends model {

    function get_all() {
        $sth = $this->db->prepare("SELECT objects.id, objects.title, objects.price, postcodes.postcode, cities.citiename, objects.mainimage, objects.sold
            FROM objects
            INNER JOIN postcodes ON objects.postcodeid = postcodes.id
            INNER JOIN cities ON postcodes.citieid = cities.id;");
        $sth->execute();
        return $sth->fetchAll();
    }

    function get($id) {
        return;
    }

    function delete($id) {
        return;
    }

    function update($id, $data) {
        return;
    }

    function create($data) {
        return;
    }

}