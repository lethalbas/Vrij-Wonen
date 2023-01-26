<?php

$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '' :
        require __DIR__ . '/../view/home_page.php';
        break;
    case '/' :
        require __DIR__ . '/../view/home_page.php';
        break;
    case '/api' :
        require __DIR__ . '/../api/api.php';
        break;
    case '/overzicht' :
        require __DIR__ . '/../view/object_overview.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/../view/404.php';
        break;
}