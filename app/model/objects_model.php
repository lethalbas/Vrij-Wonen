<?php

require_once "model.php";

class objects_model extends model {

    function get_all() {
        $sth = $this->db->prepare("SELECT objects.title, objects.price, objects.adress, postcodes.postcode, cities.citiename, objects.mainimage, objects.sold
            FROM objects
            INNER JOIN postcodes ON objects.postcodeid = postcodes.id
            INNER JOIN cities ON postcodes.citieid = cities.id;");
        $sth->execute();
        return $sth->fetchAll();
    }

    // filter by properties
    function get_all_filtered($filters) {
        $sth = $this->db->prepare("SELECT objects.title, objects.price, objects.adress, postcodes.postcode, cities.citiename, objects.mainimage, objects.sold
            FROM objects
            INNER JOIN postcodes ON objects.postcodeid = postcodes.id
            INNER JOIN cities ON postcodes.citieid = cities.id
            LEFT JOIN connectprop ON objects.id = connectprop.objectid
            LEFT JOIN properties ON connectprop.propertieid = properties.id
            WHERE properties.propertie IN ($filters)
            GROUP BY objects.id;");
        $sth->execute();
        return $sth->fetchAll();
    }

    function get($id) {
        $sth = $this->db->prepare("SELECT objects.title, objects.price, objects.adress, postcodes.postcode, cities.citiename, objects.mainimage, objects.image2, objects.image3, objects.image4, objects.image5, objects.sold
            FROM objects
            INNER JOIN postcodes ON objects.postcodeid = postcodes.id
            INNER JOIN cities ON postcodes.citieid = cities.id
            LEFT JOIN connectprop ON objects.id = connectprop.objectid
            LEFT JOIN properties ON connectprop.propertieid = properties.id
            GROUP BY objects.id;");
        $sth->execute();
        return $sth->fetch();
    }

    function delete($id) {
        $sth = $this->db->prepare("DELETE FROM objects WHERE id = $id;");
        $sth->execute();
    }

    function update($id, $data) {
        return;
    }

    function create($data) {
        return;
    }

}