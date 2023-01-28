<?php 
require_once "file_handler_util.php";

class dependencies_util {
    // arrays for dependencies
    private $cdn;
    private $custom;
    private $require;

    // include all standard dependencies
    function __construct() {
        // get cdn urls
        $file_handler_util = new file_handler_util();
        $cdn_style_path = $file_handler_util->get_cdn_style_dir();
        $cdn_script_path = $file_handler_util->get_cdn_script_dir();

        // dependencies from vendor cdns to include on each page
        $this->cdn = [
            '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">',
            '<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />',
            '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />',
            '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>',
            '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>',
            '<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>'
        ];

        // dependencies from the local cdn  to include on each page
        $this->custom = [
            '<link rel="stylesheet" href="' . $cdn_style_path . '/vendor/toast.min.css">',
            '<link rel="stylesheet" href="' . $cdn_style_path . '/reset.css">',
            '<link rel="stylesheet" href="' . $cdn_style_path . '/font_reset.css">',
            '<link rel="stylesheet" href="' . $cdn_style_path . '/main_styles.css">',
            '<script src="' . $cdn_script_path . '/vendor/jquery.redirect.js"></script>',
            '<script src="' . $cdn_script_path . '/vendor/toast.min.js"></script>',
            '<script src="' . $cdn_script_path . '/header.js"></script>'
        ];

        // php util dependencies to include on each page
        $this->require = [
            "logging_util.php",
            "file_handler_util.php",
            "user_login_session_util.php"
        ];
    }

    // echo all vendor cdn dependencies
    private function echo_cdn() {
        foreach($this->cdn as $value){
            echo $value;
        }
    }

    // echo all local cdn dependencies
    private function echo_custom() {
        foreach($this->custom as $value){
            echo $value;
        }
    }

    // require all php util dependencies
    private function include_requirements() {
        foreach($this->require as $value){
            require_once $value;
        }
    }

    // use this method in the head tag of a view to include all dependencies
    function all_dependencies() {
        $this->echo_cdn();
        $this->echo_custom();
        $this->include_requirements();
    }
}