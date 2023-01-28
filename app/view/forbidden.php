<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forbidden | Vrij Wonen</title>
    <?php 
    require_once __DIR__ . "/../util/dependencies_util.php"; 
    $dep = new dependencies_util();
    $dep->all_dependencies();
    ?>
</head>
<body>
    <?php require_once "header.php"; ?>
    <div class="container">
        <div class="row align-items-center">
            <div class="text-center mt-5">
                <h1>Forbidden</h1>
                <p class="lead">U heeft geen rechten om deze pagina te bezoeken!</p>
                <p>Klik hieronder om terug naar de startpagina te gaan.</p>
                <button type="button" class="btn btn-primary" onclick="window.location='/';">Home</button>
            </div> 
        </div>         
    </div>
</body>
</html>