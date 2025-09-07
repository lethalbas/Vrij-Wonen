<?php

require_once "model.php";

class staff_model extends model {

    // get all staff members
    function get_all() {
        $sth = $this->db->prepare("SELECT * from staff;");
        $sth->execute();
        return $sth->fetchAll();
    }

    // check if staff member exists by session key
    function get_by_session($sess) {
        $sth = $this->db->prepare("SELECT id, username, email FROM staff WHERE sessionkey = ? LIMIT 1");
        $sth->execute([$sess]);
        return $sth->fetch();
    }

    // get passwordhash username
    function get_by_user($user) {
        $sth = $this->db->prepare("SELECT id, passwordhash FROM staff WHERE username = ? LIMIT 1");
        $sth->execute([$user]);
        return $sth->fetch();
    }

    // delete staff member
    function delete($id) {
        $sth = $this->db->prepare("DELETE FROM staff WHERE id = ?");
        $sth->execute([$id]);
    }

    // set staff member session key on login
    function set_session($user, $sess) {
        $sth = $this->db->prepare("UPDATE staff SET sessionkey = ? WHERE username = ?");
        $sth->execute([$sess, $user]);
    }

    // create staff member
    function create($data) {
        $sth = $this->db->prepare("INSERT INTO staff(username,email,passwordhash) VALUES(?,?,?);");
        try{
            $sth->execute([$data["username"], $data["email"], $data["passwordhash"]]);
        }
        catch (PDOException $e)
        {
            // couldn't create staff member
            return false;
        }
        return true;
    }

    // get staff member by id
    function get_by_id($id) {
        $sth = $this->db->prepare("SELECT * FROM staff WHERE id = ?");
        $sth->execute([$id]);
        return $sth->fetch();
    }

}