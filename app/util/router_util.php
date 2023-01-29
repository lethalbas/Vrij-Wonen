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
            case '/beheerder' :
                require_once  __DIR__ . '/../view/restricted/admin_home.php';
                break;
            case '/beheerder/api-key-aanmaken' :
                require_once  __DIR__ . '/../view/restricted/create_api_key.php';
                break;
            case '/beheerder/object-aanmaken' :
            case '/beheerder/object-bewerken' :
                require_once  __DIR__ . '/../view/restricted/create_edit_object.php';
                break;
            case '/beheerder/medewerker-aanmaken' :
                require_once  __DIR__ . '/../view/restricted/create_staff.php';
                break;
            case '/beheerder/api-overzicht' :
                require_once  __DIR__ . '/../view/restricted/overview_api.php';
                break;
            case '/beheerder/contact-aanvragen-overzicht' :
                require_once  __DIR__ . '/../view/restricted/overview_inquiries.php';
                break;
            case '/beheerder/medewerkers-overzicht' :
                require_once  __DIR__ . '/../view/restricted/overview_staff.php';
                break;
            case '/beheerder/aanvraag-details' :
                require_once  __DIR__ . '/../view/restricted/inquiry_details.php';
                break;
            case '/api' :
                require_once  __DIR__ . '/../api/api.php';
                break;
            case '/forbidden' :
                require_once  __DIR__ . '/../view/forbidden.php';
                break;
            default:
                http_response_code(404);
                require_once  __DIR__ . '/../view/404.php';
                break;
        }
    }
}