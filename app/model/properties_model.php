<?php

require_once "model.php";

class properties_model extends model {

    // get all properties for select box
    function get_all() {
        $sth = $this->db->prepare("SELECT * from properties;");
        $sth->execute();
        return $sth->fetchAll();
    }

    // get all for object
    function get_all_filtered($filters) {
        $id = $filters["id"];
        $sth = $this->db->prepare("SELECT properties.id, properties.propertie from properties
            INNER JOIN connectprop ON properties.id = connectprop.propertieid
            WHERE connectprop.objectid = $id;");
        $sth->execute();
        return $sth->fetchAll();
    }

}