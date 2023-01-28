<?php 
    require_once __DIR__ . "/../controller/objects_controller.php"; 
    $searched = isset($_POST["search_array"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    if($searched){
        ?> <title>Zoekresultaten | Vrij Wonen</title> <?php
    }
    else{
        ?> <title>Alle objecten | Vrij Wonen</title> <?php
    }
    require_once __DIR__ . "/../util/dependencies_util.php"; 
    $dep = new dependencies_util();
    $dep->all_dependencies();
    $file_handler_util = new file_handler_util();
    ?>
    <link rel="stylesheet" href="<?= $file_handler_util->get_cdn_style_dir(); ?>/object_overview.css"/>
</head>
<body>
    <?php require_once "header.php"; ?>
    <div class="container mb-5">
        <div class="text-center mt-5">
            <?php
            if($searched){
                ?> <h1>Uw zoekresultaten</h1> <?php
            }
            else{
                ?> <h1>Alle objecten</h1> <?php
            }
            ?>
        </div> 
        <?php
            if($searched){
                $objects_controller = new objects_controller();
                $query = implode(",", $_POST['search_array']);
                $results = $objects_controller->get_all($query);
                if(count($results) > 0){
                    print_results($results, $file_handler_util);
                }
                else{
                    ?> 
                    <div class="text-center mt-5">
                        <span>Er zijn helaas geen resultaten voor uw zoekopdracht.</span>
                    </div> 
                    <?php
                }
            }
            else{
                $objects_controller = new objects_controller();
                $results = $objects_controller->get_all();
                if(count($results) > 0){
                    print_results($results, $file_handler_util);
                }
                else{
                    ?> 
                    <div class="text-center mt-5">
                        <span>Er zijn helaas geen objecten beschikbaar.</span>
                    </div> 
                    <?php
                }
            }

            function print_results($data, $fhu){
                ?> <div class="d-flex flex-wrap g-3 mt-5 justify-content-center align-items-center"> <?php
                foreach($data as $card){
                    $main_img = $card["mainimage"];
                    $title = $card["title"];
                    $adress = $card["adress"] . ", " . $card["citiename"];
                    ?>
                    <div class="shadow card m-2">
                        <img class="card-img-top" src="<?= $fhu->get_cdn_user_img_dir() . "/" . $main_img . ".jpg"; ?>" alt="Hoofdfoto">
                        <div class="card-body col d-flex flex-column justify-content-between">
                            <h5 class="card-title"><?= $title; ?></h5>
                            <p class="card-text"><i class="fa-solid fa-location-dot"></i> <?= $adress; ?></p>
                        </div>
                        <div class="card-footer">  
                            <button class="btn btn-primary"><i class="fa-solid fa-circle-info"></i>Meer details</button>
                        </div>
                    </div>
                    <?php
                }
                ?> </div> <?php
            }
        ?>
    </div>
</body>
</html>

<?php
    $logging_util = new logging_util();
    $logging_util->create_log("object_overview.php");
?>