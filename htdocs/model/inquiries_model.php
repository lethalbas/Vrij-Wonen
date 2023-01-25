<?php

require_once "model.php";

class inquiries_model extends model {

    function get_all() {
        $sth = $this->db->prepare("SELECT * from inquiries;");
        $sth->execute();
        return $sth->fetchAll();
    }

}