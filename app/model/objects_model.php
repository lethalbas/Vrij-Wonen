<?php

require_once "model.php";

class objects_model extends model {

    function get_all() {
        $sth = $this->db->prepare("SELECT objects.id, objects.title, objects.adress, cities.citiename, objects.mainimage
            FROM objects
            INNER JOIN cities ON objects.cityid = cities.id");
        $sth->execute();
        return $sth->fetchAll();
    }

    // filter by properties
    function get_all_filtered($filters) {
        $filterstring = "";
        if(count($filters) > 0){
            $filterstring = $filterstring . "WHERE";
            foreach($filters as $filter => $val) {
                // arrays
                if(is_array($val)){
                    foreach($val as $arr_item){
                        // strings in arrays
                        if(is_string($val)){
                            $newstr = " $filter = '$arr_item' AND";
                            $filterstring = $filterstring . $newstr;
                        }
                        // integers in arrays (used for id)
                        else{
                            $newstr = " $filter = $arr_item AND";
                            $filterstring = $filterstring . $newstr;
                        }
                    }
                }
                // strings
                if(is_string($val)){
                    $newstr = " $filter = '$val' AND";
                    $filterstring = $filterstring . $newstr;
                }
                // integers (used for id)
                else{
                    $newstr = " $filter = $val AND";
                    $filterstring = $filterstring . $newstr;
                }
            }
            $filterstring = rtrim($filterstring, ' AND');
        }
        $sth = $this->db->prepare("SELECT objects.id, objects.title, objects.adress, cities.citiename, objects.mainimage
        FROM objects
        INNER JOIN cities ON objects.cityid = cities.id
        LEFT JOIN connectprop ON objects.id = connectprop.objectid
        $filterstring
        GROUP BY objects.id;");
        $sth->execute();
        return $sth->fetchAll();
    }

    function get($id) {
        $sth = $this->db->prepare("SELECT objects.title, objects.adress, cities.citiename, objects.mainimage, objects.image2, objects.image3, objects.image4, objects.image5
            FROM objects
            INNER JOIN cities ON objects.cityid = cities.id
            LEFT JOIN connectprop ON objects.id = connectprop.objectid
            LEFT JOIN properties ON connectprop.propertieid = properties.id
            WHERE objects.id = $id
            GROUP BY objects.id;");
        $sth->execute();
        return $sth->fetch();
    }

    function delete($id) {
        $this->db->beginTransaction();
        $sth = $this->db->prepare("DELETE FROM connectprop WHERE objectid = $id;");
        $sth->execute();
        $sth = $this->db->prepare("DELETE FROM objects WHERE id = $id;");
        $sth->execute();
        $this->db->commit();
    }

    function update($id, $data) {
        return;
    }

    function create($data) {
        return;
    }

}