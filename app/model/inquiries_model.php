<?php

require_once "model.php";

class inquiries_model extends model {

    // get all unhandled inquiries
    function get_all() {
        $sth = $this->db->prepare("SELECT * FROM inquiries WHERE handled = 0;");
        $sth->execute();
        return $sth->fetchAll();
    }

    function get($id) {
        return;
    }

    // set handled to true so the inquiry stays archived but doesn't show up on the site anymore
    function delete($id) {
        $sth = $this->db->prepare("UPDATE inquiries SET handled = 1 WHERE id = $id;");
        $sth->execute();
    }

    function create($data) {
        return;
    }

}