<?php

class file_handler_util {
    // variables for cdn paths
    private $cdn_style_dir;
    private $cdn_script_dir;
    private $cdn_img_dir;
    private $cdn_user_img_dir;

    // set all required variables
    function __construct() {
        if(isset($_SERVER['HTTPS'])){
            $cdn_url = "https://" . $_SERVER['HTTP_HOST'];
        }
        else{
            $cdn_url = "http://" . $_SERVER['HTTP_HOST'];
        }
        $this->cdn_style_dir = $cdn_url . "/cdn/style";
        $this->cdn_script_dir = $cdn_url . "/cdn/script";
        $this->cdn_img_dir = $cdn_url . "/cdn/img";
        $this->cdn_user_img_dir = $this->cdn_img_dir . "/user_image_uploads";
    }

    // get the directory for stylesheets
    function get_cdn_style_dir () {
        return $this->cdn_style_dir;
    }

    // get the directory for javascript files
    function get_cdn_script_dir () {
        return $this->cdn_script_dir;
    }

    // get the directory for static images
    function get_cdn_img_dir () {
        return $this->cdn_img_dir;
    }

    // get the directory for user submitted images
    function get_cdn_user_img_dir () {
        return $this->cdn_user_img_dir;
    }

    // upload image to cdn and return filename without extension
    function upload($image) {
        $upload_dir = __DIR__ . "\..\..\htdocs\cdn\img\user_image_uploads\\";
        $glob = glob("$upload_dir\\*.jpg");
        array_push($glob, 0);
        $highest = max(preg_replace("|[^0-9]|", "", $glob));
        $newfile = $highest + 1;
        $destination  = "$upload_dir/$newfile.jpg";
        $src = $image["tmp_name"];
        move_uploaded_file( $src, $destination );
        return $newfile;
    }

    
}