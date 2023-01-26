<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beheren | Vrij Wonen</title>
    <?php 
    require_once __DIR__ . "/../util/dependencies_util.php"; 
    $dep = new dependencies_util();
    $dep->all_dependencies();
    ?>
</head>
<body>
    
</body>
</html>

<?php
    $logging_util = new logging_util();
    $logging_util->create_log("admin_home.php");
?>