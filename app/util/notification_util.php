<?php

class notification_util {
    // set notification cookie (gets handled in main.js)
    function notify($title, $body){
        setcookie("notification_title", $title, time() + (86400 * 30), "/");
        setcookie("notification", $body, time() + (86400 * 30), "/");
    }
}