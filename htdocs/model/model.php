<?php

require_once __DIR__ . "/../util/db_connection_util.php";

abstract class model {
    private $connection_util;
    protected $db;

    function __construct() {
        $this->connection_util = new db_connection_util();
        $this->db = $this->connection_util->get_db();
    }
}