<?php

require_once "model.php";

class staff_model extends model {

    function get_all() {
        $sth = $this->db->prepare("SELECT * from staff;");
        $sth->execute();
        return $sth->fetchAll();
    }

    function get_by_session($sess) {
        $sth = $this->db->prepare("SELECT `admin` FROM staff WHERE sessionkey = '$sess' LIMIT 1;");
        $sth->execute();
        return $sth->fetch();
    }

    function get_by_user($user) {
        $sth = $this->db->prepare("SELECT id, passwordhash FROM staff WHERE username = '$user' LIMIT 1;");
        $sth->execute();
        return $sth->fetch();
    }

    function delete($id) {
        $sth = $this->db->prepare("DELETE FROM staff WHERE id = $id AND `admin` = 0;");
        $sth->execute();
    }

    function set_session($user, $sess) {
        $sth = $this->db->prepare("UPDATE staff SET sessionkey = '$sess' WHERE username = '$user';");
        $sth->execute();
    }

    function create($data) {
        $sth = $this->db->prepare("INSERT INTO staff(username,email,passwordhash,`admin`) VALUES(?,?,?,?);");
        try{
            $sth->execute([$data["username"], $data["email"], $data["passwordhash"], $data["admin"]]);
        }
        catch (PDOException $e)
        {
            return false;
        }
        return true;
    }

}