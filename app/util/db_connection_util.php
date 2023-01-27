<?php

class db_connection_util {
    // database login info
    private $db_name = "vrijwonen";
    private $user = "root";
    private $pass = "";

    // database variable
    private $db;

    // create new pdo connection
    function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=' . $this->db_name, $this->user, $this->pass, array(
            PDO::ATTR_PERSISTENT => true
        ));
    }

    // get the db variable
    function get_db() {
        return $this->db;
    }

}