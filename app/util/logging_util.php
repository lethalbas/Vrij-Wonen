<?php

class logging_util {
    // variables for log file names and paths
    private $log_folder = __DIR__ . "/../system_logs/";
    private $log_file_location;
    private $file_date_time;

    // define variables
    function __construct() {
        $this->file_date_time = date('d-m-y_H');
        $this->log_file_location = $this->log_folder . $this->file_date_time . ".txt";
    }

    // create a system log
    function create_log($page_file) {
        $date_time = date('d-m-y H:i:s');
        $log_txt = "IP Adress: " . $_SERVER['REMOTE_ADDR'] . ", requested view $page_file at: $date_time\n";
        $file = fopen($this->log_file_location, 'a');
        fwrite($file, $log_txt);  
        fclose($file);
    }

    // create custom error log
    function create_custom_log($log) {
        $date_time = date('d-m-y H:i:s');
        $log_txt = "IP Adress: " . $_SERVER['REMOTE_ADDR'] . "triggered log creation: '" . $log . "' at: $date_time\n";
        $file = fopen($this->log_file_location, 'a');
        fwrite($file, $log_txt);  
        fclose($file);
    }
}