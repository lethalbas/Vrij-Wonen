<?php

require_once "model.php";

class staff_model extends model {

    function get_all() {
        $sth = $this->db->prepare("SELECT * from staff;");
        $sth->execute();
        return $sth->fetchAll();
    }

}