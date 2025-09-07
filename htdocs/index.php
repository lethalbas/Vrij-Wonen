<?php

require_once __DIR__ . "/app/util/router_util.php";

$router_util = new router_util();
$request = $_SERVER['REQUEST_URI'];

$router_util->route($request);