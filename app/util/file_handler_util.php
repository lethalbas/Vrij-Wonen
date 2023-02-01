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
        $ext = pathinfo($image["name"], PATHINFO_EXTENSION);
        // Check for correct file extension
        if ($ext !== 'jpg') {
            return "error";
        }
        $upload_dir = __DIR__ . "/../../htdocs/cdn/img/user_image_uploads";
        $img_name = strval(time()) . strval(rand(0, 99999));
        $destination = "$upload_dir/$img_name.jpg";
        $src = $image["tmp_name"];
        if (move_uploaded_file($src, $destination)) {
            return $img_name;
        }
        return 'error';
    }

    // delete image from cdn user uploaded images dir
    function delete($name){
        $upload_dir = $_SERVER['DOCUMENT_ROOT']  . "/cdn/img/user_image_uploads/";
        $img = realpath($upload_dir . $name . ".jpg");
        unlink($img);
    }
    
}