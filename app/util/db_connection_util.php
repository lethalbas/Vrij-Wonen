<?php

require_once __DIR__ . "/config_util.php";

class db_connection_util {
    // database login info
    private $db_name;
    private $user;
    private $pass;
    private $host;

    // database variable
    private $db;

    // create new pdo connection
    function __construct() {
        $this->host = config_util::get('db_host', 'localhost');
        $this->db_name = config_util::get('db_name', 'vrijwonen');
        $this->user = config_util::get('db_user', 'root');
        $this->pass = config_util::get('db_pass', '');
        
        $this->db = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->user, $this->pass, array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ));
    }

    // get the db variable
    function get_db() {
        return $this->db;
    }

}