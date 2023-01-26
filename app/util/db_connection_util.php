<?php

class db_connection_util {

    private $user = "root";
    private $pass = "";
    private $db;

    function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=vrijwonen', $this->user, $this->pass, array(
            PDO::ATTR_PERSISTENT => true
        ));
    }

    function get_db() {
        return $this->db;
    }

}