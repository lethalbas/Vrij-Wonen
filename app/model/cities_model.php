<?php

require_once "model.php";

class cities_model extends model {

    function get_all() {
        $sth = $this->db->prepare("SELECT * from cities;");
        $sth->execute();
        return $sth->fetchAll();
    }

}