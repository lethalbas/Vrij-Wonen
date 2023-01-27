<?php

require_once "model.php";

class postcodes_model extends model {

    function get_all() {
        $sth = $this->db->prepare("SELECT * from postcodes;");
        $sth->execute();
        return $sth->fetchAll();
    }

    function create($data) {
        return;
    }

}