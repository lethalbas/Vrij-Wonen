<?php
session_start();
require_once __DIR__ . "/../controller/objects_controller.php"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Objectinformatie | Vrij Wonen</title>
    <?php 
    require_once __DIR__ . "/../util/dependencies_util.php"; 
    $dep = new dependencies_util();
    $dep->all_dependencies();
    $file_handler_util = new file_handler_util();
    ?>
    <link rel="stylesheet" href="<?= $file_handler_util->get_cdn_style_dir(); ?>/object_details.css">
</head>
<body>
    <?php require_once "header.php"; ?>
    <div class="container mb-5">
        <?php if(isset($_POST["id"])){ 
            $oc = new objects_controller();
            $data = $oc->get($_POST["id"]);
            $properties = $data["properties"];
            $data = $data["object"];
            ?>
            <div class="card mt-5 shadow">
                <div class="container-fluid">
                    <div class="wrapper row">
                        <div class="preview col-md-6 ">
                            
                            <div class="preview-pic tab-content m-2">
                            <img class="main_image" src="<?= $file_handler_util->get_cdn_user_img_dir() . "/" . $data["mainimage"] . ".jpg"; ?>" />
                            </div>
                            <ul class="preview-thumbnail nav nav-tabs mb-1">
                            <li class="m-1"><img class="detail_image" src="<?= $file_handler_util->get_cdn_user_img_dir() . "/" . $data["image2"] . ".jpg"; ?>" /></li>
                            <li class="m-1"><img class="detail_image" src="<?= $file_handler_util->get_cdn_user_img_dir() . "/" . $data["image3"] . ".jpg"; ?>" /></li>
                            <li class="m-1"><img class="detail_image" src="<?= $file_handler_util->get_cdn_user_img_dir() . "/" . $data["image4"] . ".jpg"; ?>" /></li>
                            <li class="m-1"><img class="detail_image" src="<?= $file_handler_util->get_cdn_user_img_dir() . "/" . $data["image5"] . ".jpg"; ?>" /></li>
                            </ul>
                            
                        </div>
                        <div class="details col-md-6">
                            <h2 class="mt-5"><?= $data["title"]; ?></h2>
                            <p class="mt-2"><i class="fa-solid fa-location-dot"></i> <?= $data["adress"] . ", " . $data["postcode"] . ", " . $data["citiename"]; ?></p>
                            <p class="mt-2"><?= $data["description"]; ?></p>
                            <h5 class="mt-5">Vraagprijs: <span>€<?= $data["price"]; ?></span></h5>
                            <?php if($data["sold"] == 0) { ?>
                                <h5 class="mt-2">Verkoopstatus: <span>Beschikbaar</span></h5>
                            <?php } else { ?>
                                <h5 class="mt-2">Verkoopstatus: <span>Verkocht</span></h5>
                            <?php } ?>
                            <h5 class="mt-5">Object-eigenschappen:</h5>
                            <ul>
                                <?php foreach ($properties as $prop) { ?>
                                    <li><span class="size"><?= $prop["propertie"]; ?></span></li>
                                <?php } ?>
                            </ul>
                            <button class="btn btn-primary mt-3 mb-5" type="button">Neem contact op over dit object</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</body>
</html>

<?php
    $logging_util = new logging_util();
    $logging_util->create_log("object_details.php");
?>