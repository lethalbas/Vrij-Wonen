<?php

require_once "model.php";

class inquiries_model extends model {

    // get all unhandled inquiries
    function get_all() {
        $sth = $this->db->prepare("SELECT * FROM inquiries ORDER BY handled;");
        $sth->execute();
        return $sth->fetchAll();
    }

    function create($data) {
        $sth = $this->db->prepare("INSERT INTO inquiries(`objectid`,`fullname`,`replyemail`,`message`) VALUES(?,?,?,?);");
        try{
            $sth->execute([$data["object"], $data["fullname"], $data["replyemail"], $data["message"]]);
            return true;
        }
        catch (PDOException $e)
        {
            return false;
        }
    }

    // set handled to true so the inquiry stays archived but doesn't show up on the site anymore
    function complete_inquiry($id){
        $sth = $this->db->prepare("UPDATE inquiries SET handled = 1 WHERE id = $id;");
        $sth->execute();
    }

}