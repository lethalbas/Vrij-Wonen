<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alle medewerkers | Vrij Wonen</title>
    <?php 
    require_once __DIR__ . "/../../util/dependencies_util.php"; 
    $dep = new dependencies_util();
    $dep->all_dependencies();
    $ulsu = new user_login_session_util();
    // restricted page
    if($ulsu->get_login_status() < 2){
        header('Location: /forbidden'); 
        exit;
    }
    ?>
</head>
<body>
    <?php require_once __DIR__ . "/../header.php"; ?>
    <div class="container mb-5">
        
    </div>
</body>
</html>

<?php
    $logging_util = new logging_util();
    $logging_util->create_log("overview_staff.php");
?>