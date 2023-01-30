<?php

require_once "model.php";

class staff_model extends model {

    // get all staff members
    function get_all() {
        $sth = $this->db->prepare("SELECT * from staff;");
        $sth->execute();
        return $sth->fetchAll();
    }

    // check if staff member is admin by session key
    function get_by_session($sess) {
        $sth = $this->db->prepare("SELECT `admin` FROM staff WHERE sessionkey = '$sess' LIMIT 1;");
        $sth->execute();
        return $sth->fetch();
    }

    // get passwordhash username
    function get_by_user($user) {
        $sth = $this->db->prepare("SELECT id, passwordhash FROM staff WHERE username = '$user' LIMIT 1;");
        $sth->execute();
        return $sth->fetch();
    }

    // delete staff member (only delete if the staff member isn't an administrator)
    function delete($id) {
        $sth = $this->db->prepare("DELETE FROM staff WHERE id = $id AND `admin` = 0;");
        $sth->execute();
    }

    // set staff member session key on login
    function set_session($user, $sess) {
        $sth = $this->db->prepare("UPDATE staff SET sessionkey = '$sess' WHERE username = '$user';");
        $sth->execute();
    }

    // create staff member
    function create($data) {
        $sth = $this->db->prepare("INSERT INTO staff(username,email,passwordhash,`admin`) VALUES(?,?,?,?);");
        try{
            $sth->execute([$data["username"], $data["email"], $data["passwordhash"], $data["admin"]]);
        }
        catch (PDOException $e)
        {
            // couldn't create staff member
            return false;
        }
        return true;
    }

}