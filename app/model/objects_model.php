<?php

require_once "model.php";

class objects_model extends model {

    function get_all() {
        $sth = $this->db->prepare("SELECT objects.id, objects.title, objects.adress, cities.citiename, objects.mainimage
            FROM objects
            INNER JOIN postcodes ON objects.postcodeid = postcodes.id
            INNER JOIN cities ON postcodes.citieid = cities.id;");
        $sth->execute();
        return $sth->fetchAll();
    }

    // filter by properties
    function get_all_filtered($filters) {
        $citie = $filters["citie"];
        $filters = $filters["properties"];
        $sth = "";
        if($citie != "" && $filters != "") {
            $sth = $this->db->prepare("SELECT objects.id, objects.title, objects.adress, cities.citiename, objects.mainimage
                FROM objects
                INNER JOIN postcodes ON objects.postcodeid = postcodes.id
                INNER JOIN cities ON postcodes.citieid = cities.id
                LEFT JOIN connectprop ON objects.id = connectprop.objectid
                LEFT JOIN properties ON connectprop.propertieid = properties.id
                WHERE properties.id IN ($filters) AND cities.id = $citie
                GROUP BY objects.id;");
        }
        else if ($citie != "") {
            $sth = $this->db->prepare("SELECT objects.id, objects.title, objects.adress, cities.citiename, objects.mainimage
                FROM objects
                INNER JOIN postcodes ON objects.postcodeid = postcodes.id
                INNER JOIN cities ON postcodes.citieid = cities.id
                LEFT JOIN connectprop ON objects.id = connectprop.objectid
                LEFT JOIN properties ON connectprop.propertieid = properties.id
                WHERE cities.id = $citie
                GROUP BY objects.id;");
        }
        else {
            $sth = $this->db->prepare("SELECT objects.id, objects.title, objects.adress, cities.citiename, objects.mainimage
                FROM objects
                INNER JOIN postcodes ON objects.postcodeid = postcodes.id
                INNER JOIN cities ON postcodes.citieid = cities.id
                LEFT JOIN connectprop ON objects.id = connectprop.objectid
                LEFT JOIN properties ON connectprop.propertieid = properties.id
                WHERE properties.id IN ($filters)
                GROUP BY objects.id;");
        }
        $sth->execute();
        return $sth->fetchAll();
    }

    function get($id) {
        $sth = $this->db->prepare("SELECT objects.title, objects.adress, cities.citiename, objects.mainimage, objects.image2, objects.image3, objects.image4, objects.image5
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