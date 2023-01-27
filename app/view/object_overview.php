<?php 
    require_once __DIR__ . "/../controller/objects_controller.php"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alle objecten | Vrij Wonen</title>
    <?php 
    require_once __DIR__ . "/../util/dependencies_util.php"; 
    $dep = new dependencies_util();
    $dep->all_dependencies();
    $file_handler_util = new file_handler_util();
    ?>
    <link rel="stylesheet" href="<?=$file_handler_util->get_cdn_style_dir(); ?>/object_overview.css">
</head>
<body>
    <?php require_once "header.php"; ?>
    <div class="container mb-5">
        <div class="text-center mt-5">
            <h1>Alle objecten</h1>
        </div> 
        <div class="d-flex flex-wrap g-3 mt-5 justify-content-center align-items-center">
            <div class="card m-2">
                <img class="card-img-top" src="<?= $file_handler_util->get_cdn_user_img_dir(); ?>/1.jpg" alt="Hoofdfoto">
                <div class="card-body col d-flex flex-column justify-content-between">
                    <h5 class="card-title">Vakantiewoning met eigen zwembad</h5>
                    <p class="card-text"><i class="fa-solid fa-location-dot"></i> Zoete Ermgaard 43, Amersfoort</p>
                </div>
                <div class="card-footer">  
                    <button class="btn btn-primary"><i class="fa-solid fa-circle-info"></i> Meer details</button>
                </div>
            </div>
            <div class="card m-2">
                <img class="card-img-top" src="<?= $file_handler_util->get_cdn_user_img_dir(); ?>/1.jpg" alt="Hoofdfoto">
                <div class="card-body col d-flex flex-column justify-content-between">
                    <h5 class="card-title">Vakantiewoning met keuken</h5>
                    <p class="card-text"><i class="fa-solid fa-location-dot"></i> Zoete Ermgaard 43, Amersfoort</p>
                </div>
                <div class="card-footer">  
                    <button class="btn btn-primary"><i class="fa-solid fa-circle-info"></i> Meer details</button>
                </div>
            </div>
            <div class="card m-2">
                <img class="card-img-top" src="<?= $file_handler_util->get_cdn_user_img_dir(); ?>/1.jpg" alt="Hoofdfoto">
                <div class="card-body col d-flex flex-column justify-content-between">
                    <h5 class="card-title">Vakantiewoning met achtertuin</h5>
                    <p class="card-text"><i class="fa-solid fa-location-dot"></i> Zoete Ermgaard 43, Amersfoort</p>
                </div>
                <div class="card-footer">  
                    <button class="btn btn-primary"><i class="fa-solid fa-circle-info"></i> Meer details</button>
                </div>
            </div>
            <div class="card m-2">
                <img class="card-img-top" src="<?= $file_handler_util->get_cdn_user_img_dir(); ?>/1.jpg" alt="Hoofdfoto">
                <div class="card-body col d-flex flex-column justify-content-between">
                    <h5 class="card-title">Vakantiewoning met achtertuin</h5>
                    <p class="card-text"><i class="fa-solid fa-location-dot"></i> Zoete Ermgaard 43, Amersfoort</p>
                </div>
                <div class="card-footer">  
                    <button class="btn btn-primary"><i class="fa-solid fa-circle-info"></i> Meer details</button>
                </div>
            </div>
            <div class="card m-2">
                <img class="card-img-top" src="<?= $file_handler_util->get_cdn_user_img_dir(); ?>/1.jpg" alt="Hoofdfoto">
                <div class="card-body col d-flex flex-column justify-content-between">
                    <h5 class="card-title">Vakantiewoning met achtertuin</h5>
                    <p class="card-text"><i class="fa-solid fa-location-dot"></i> Zoete Ermgaard 43, Amersfoort</p>
                </div>
                <div class="card-footer ">  
                    <button class="btn btn-primary"><i class="fa-solid fa-circle-info"></i> Meer details</button>
                </div>
            </div>
            <div class="card m-2">
                <img class="card-img-top" src="<?= $file_handler_util->get_cdn_user_img_dir(); ?>/1.jpg" alt="Hoofdfoto">
                <div class="card-body col d-flex flex-column justify-content-between">
                    <h5 class="card-title">Vakantiewoning met achtertuin</h5>
                    <p class="card-text"><i class="fa-solid fa-location-dot"></i> Zoete Ermgaard 43, Amersfoort</p>
                </div>
                <div class="card-footer">  
                    <button class="btn btn-primary"><i class="fa-solid fa-circle-info"></i> Meer details</button>
                </div>
            </div>
            <div class="card m-2">
                <img class="card-img-top" src="<?= $file_handler_util->get_cdn_user_img_dir(); ?>/1.jpg" alt="Hoofdfoto">
                <div class="card-body col d-flex flex-column justify-content-between">
                    <h5 class="card-title">Vakantiewoning met achtertuin</h5>
                    <p class="card-text"><i class="fa-solid fa-location-dot"></i> Zoete Ermgaard 43, Amersfoort</p>
                </div>
                <div class="card-footer">  
                    <button class="btn btn-primary"><i class="fa-solid fa-circle-info"></i> Meer details</button>
                </div>
            </div>
            <div class="card m-2">
                <img class="card-img-top" src="<?= $file_handler_util->get_cdn_user_img_dir(); ?>/1.jpg" alt="Hoofdfoto">
                <div class="card-body col d-flex flex-column justify-content-between">
                    <h5 class="card-title">Vakantiewoning met achtertuin</h5>
                    <p class="card-text"><i class="fa-solid fa-location-dot"></i> Zoete Ermgaard 43, Amersfoort</p>
                </div>
                <div class="card-footer">  
                    <button class="btn btn-primary"><i class="fa-solid fa-circle-info"></i> Meer details</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
    $logging_util = new logging_util();
    $logging_util->create_log("object_overview.php");
?>