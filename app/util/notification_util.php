<?php

class notification_util {
    function notify($title, $body){
        setcookie("notification_title", $title, time() + (86400 * 30), "/");
        setcookie("notification", $body, time() + (86400 * 30), "/");
    }
}