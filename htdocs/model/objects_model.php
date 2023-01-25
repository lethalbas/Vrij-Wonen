<?php

require_once "model.php";

class objects_model extends model {

    function get_all() {
        $sth = $this->db->prepare("SELECT * from objects;");
        $sth->execute();
        return $sth->fetchAll();
    }

}