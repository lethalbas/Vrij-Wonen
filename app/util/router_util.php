<?php

class router_util {
    // use this method in index.php to route to the desired views
    function route($request) {
        switch ($request) {
            case '' :
            case '/' :
                require_once  __DIR__ . '/../view/home_page.php';
                break;
            case '/objecten-overzicht' :
                require_once  __DIR__ . '/../view/object_overview.php';
                break;
            case '/object-details' :
                require_once  __DIR__ . '/../view/object_details.php';
                break;
            case '/log-in' :
                require_once  __DIR__ . '/../view/log_in.php';
                break;
            case '/beheerder-startpagina' :
                require_once  __DIR__ . '/../view/restricted/admin_home.php';
                break;
            case '/api-key-aanmaken' :
                require_once  __DIR__ . '/../view/restricted/create_api_key.php';
                break;
            case '/object-aanmaken' :
            case '/object-bewerken' :
                require_once  __DIR__ . '/../view/restricted/create_edit_object.php';
                break;
            case '/medewerker-aanmaken' :
            case '/medewerker-bewerken' :
                require_once  __DIR__ . '/../view/restricted/create_edit_staff.php';
                break;
            case '/api-overzicht' :
                require_once  __DIR__ . '/../view/restricted/overview_api.php';
                break;
            case '/contact-aanvragen-overzicht' :
                require_once  __DIR__ . '/../view/restricted/overview_inquiries.php';
                break;
            case '/medewerkers-overzicht' :
                require_once  __DIR__ . '/../view/restricted/overview_staff.php';
                break;
            case '/inquiry' :
                require_once  __DIR__ . '/../view/restricted/create_edit_object.php';
                break;
            case '/api' :
                require_once  __DIR__ . '/../api/api.php';
                break;
            default:
                http_response_code(404);
                require_once  __DIR__ . '/../view/404.php';
                break;
        }
    }
}