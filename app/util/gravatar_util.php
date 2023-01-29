<?php

require_once "file_handler_util.php";

class gravatar_util {
    private $fhu;
    private $default = "";
    private $size = 30;

    function __construct() {
        $this->fhu = new file_handler_util;
        // the image has to be hosted on a public cdn to show up as gravatars default image,
        // when hosting the app on a local enviroment default images will not be loaded and will simply show the alt text
        $this->default = $this->fhu->get_cdn_img_dir() . "/default_avatar.jpg";
    }

    // get image source url from gravatar
    function get_gravatar_url($email){
        return "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $this->default ) . "&s=" . $this->size;
    }
}