<?php

class router_util {
    // use this method in index.php to route to the desired views
    function route($request) {
        switch ($request) {
            case '' :
                require_once  __DIR__ . '/../view/home_page.php';
                break;
            case '/' :
                require_once  __DIR__ . '/../view/home_page.php';
                break;
            case '/api' :
                require_once  __DIR__ . '/../api/api.php';
                break;
            case '/overzicht' :
                require_once  __DIR__ . '/../view/object_overview.php';
                break;
            default:
                http_response_code(404);
                require_once  __DIR__ . '/../view/404.php';
                break;
        }
    }
}