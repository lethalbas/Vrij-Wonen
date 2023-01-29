<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Vrij Wonen</title>
    <?php 
    require_once __DIR__ . "/../util/dependencies_util.php"; 
    $dependencies_util = new dependencies_util();
    $dependencies_util->all_dependencies();
    $file_handler_util = new file_handler_util();
    ?>
    <link rel="stylesheet" href="<?=$file_handler_util->get_cdn_style_dir(); ?>/home_page.css">
</head>
<body>
    <?php require_once "header.php"; ?>
    <div class="container mb-5">
        <div class="row align-items-center">
            <div class="text-center mt-5">
                <h1>Vrij Wonen</h1>
                <p class="lead">Welkom op de startpagina van vrij wonen!</p>
                <p>Klik hieronder om vrijblijvend door onze objecten te browsen.</p>
                <button type="button" class="btn btn-primary" onclick="window.location='/objecten-overzicht';">Overzicht</button>
            </div> 
        </div>         
    </div>
</body>
</html>

<?php
    $logging_util = new logging_util();
    $logging_util->create_log("home_page.php");
?>