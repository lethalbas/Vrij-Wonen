<?php

require_once "model.php";

class api_model extends model {

    function get_all() {
        $sth = $this->db->prepare("SELECT * from apikeys;");
        $sth->execute();
        return $sth->fetchAll();
    }

}