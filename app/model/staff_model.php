<?php

require_once "model.php";

class staff_model extends model {

    function get_all() {
        $sth = $this->db->prepare("SELECT * from staff;");
        $sth->execute();
        return $sth->fetchAll();
    }

    function delete($id) {
        $sth = $this->db->prepare("DELETE FROM staff WHERE id = $id;");
        $sth->execute();
    }

    // change session key or password
    function update($id, $data) {
        return;
    }

    function create($data) {
        return;
    }

}