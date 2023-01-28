<?php 
    require_once __DIR__ . "/../controller/objects_controller.php"; 
    $searched = isset($_POST["properties"]) || isset($_POST["citie"]);
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
    <script src="<?= $file_handler_util->get_cdn_script_dir(); ?>/object_overview.js"></script>
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
        <!-- filters form -->
        <div class="d-flex justify-content-center align-items-center mt-5">
            <div id="form-container-responsive" class="shadow w-50 p-3 border rounded">
                <form method="post", action="/objecten-overzicht">
                    <!-- Username input -->
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Eigenschappen</label>
                        <select id="js-properties-multiple" class="form-select" data-control="select2" name="properties[]" multiple="multiple">
                            <option value="Dicht bij zee">Dicht bij zee</option>
                            <option value="Dicht bij stad">Dicht bij stad</option>
                        </select>
                    </div>

                    <!-- Password input -->
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example2">Stad</label>
                        <select id="js-citie-single" class="form-select" data-control="select2" name="citie">
                            <option></option>
                            <option value="Amersfoort">Amersfoort</option>
                            <option value="Utrecht">Utrecht</option>
                        </select>
                    </div>

                    <hr/>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-block">Zoeken</button>
                </form>
            </div>
        </div>
        <!-- print all object cards -->
        <?php
            if($searched){
                $objects_controller = new objects_controller();
                $query = "";
                if(isset($_POST["properties"])){
                    $query = implode(",", $_POST["properties"]);
                }
                $results = $objects_controller->get_all($query, $_POST["citie"]);
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