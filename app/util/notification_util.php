<?php

class notification_util {
    function notify($title, $body){
        setcookie("notification_title", $title);
        setcookie("notification", $body);
    }
}