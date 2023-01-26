<?php

class file_handler_util {
    private $style_dir;
    private $script_dir;
    private $img_dir;
    private $user_img_dir;

    function __construct() {
        $domain = "http://" . $_SERVER['HTTP_HOST'];
        $this->style_dir = $domain . "/style";
        $this->script_dir = $domain . "/script";
        $this->img_dir = $domain . "/img";
        $this->user_img_dir = $this->img_dir . "/user_image_uploads";
    }

    function get_style_dir () {
        return $this->style_dir;
    }

    function get_script_dir () {
        return $this->script_dir;
    }

    function get_img_dir () {
        return $this->img_dir;
    }

    function get_user_img_dir () {
        return $this->user_img_dir;
    }
}