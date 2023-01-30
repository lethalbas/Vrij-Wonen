<?php
session_start();
require_once __DIR__ . "/../controller/objects_controller.php"; 
require_once __DIR__ . "/../controller/properties_controller.php"; 
require_once __DIR__ . "/../controller/cities_controller.php"; 
$searched = isset($_POST["properties"]) || isset($_POST["citie"]);
if(isset($_POST["properties"])){
    $prop = implode(",", $_POST["properties"]);
}
if($searched){
    $searchfilters = array();
    if(isset($_POST["properties"])){
        $searchfilters["connectprop.propertieid"]= $prop;
    }
    if(isset($_POST["citie"])){
        $searchfilters["cities.id"]= $_POST["citie"];
    }
}
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
    $ulsu = new user_login_session_util();
    ?>
    <link rel="stylesheet" href="<?= $file_handler_util->get_cdn_style_dir(); ?>/object_overview.css">
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
                ?> 
                <h1>Alle objecten</h1>
                <p>U kunt de resultaten filteren via onderstaand formulier.</p>
                <?php
            }
            ?>
        </div> 

        <!-- search form -->
        <div class="d-flex justify-content-center align-items-center mt-5">
            <div id="form-container-responsive" class="shadow w-50 p-3 border rounded">
                <form method="post", action="/objecten-overzicht">
                    
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Eigenschappen </label>
                        <select id="js-properties-multiple" class="form-select" data-control="select2" name="properties[]" multiple="multiple">
                        <?php
                            $pc = new properties_controller();
                            $options = $pc->get_all();
                            foreach($options as $option) {
                                ?> <option value="<?= $option["id"]; ?>"><?= $option["propertie"]; ?></option> <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example2">Stad </label>
                        <select id="js-citie-single" class="form-select" data-control="select2" name="citie">
                            <option></option>
                            <?php
                            $cc = new cities_controller();
                            $options = $cc->get_all();
                            foreach($options as $option) {
                                ?> <option value="<?= $option["id"]; ?>"><?= $option["citiename"]; ?></option> <?php
                            }
                            ?>
                        </select>
                    </div>

                    <hr/>

                    <?php if($ulsu->get_login_status() > 0){ ?>
                    <div class="d-flex justify-content-between">
                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary btn-block float-left "><i class="fas fa-search"></i> Zoeken</button>

                        <!-- Create button -->
                        <button id="add_btn" class="btn btn-primary btn-block float-right"><i class="fas fa-plus"></i> Object aanmaken</button>
                    </div>
                    <?php } else{ ?>
                    <button type="submit" class="btn btn-primary btn-block float-left "><i class="fas fa-search"></i> Zoeken</button>
                    <?php } ?>
                </form>
            </div>
        </div>

        <!-- handle object data -->
        <?php
            if($searched){
                $objects_controller = new objects_controller();
                $query = "";
                $results = $objects_controller->get_all($searchfilters);
                if(count($results) > 0){
                    print_results($results, $file_handler_util, $ulsu);
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
                    print_results($results, $file_handler_util, $ulsu);
                }
                else{
                    ?> 
                    <div class="text-center mt-5">
                        <span>Er zijn helaas geen objecten beschikbaar.</span>
                    </div> 
                    <?php
                }
            }

            // print cards based on data
            // TODO: look into $fhu variable
            function print_results($data, $fhu, $ulsu){
                ?> <div class="d-flex flex-wrap g-3 mt-5 justify-content-center align-items-center"> <?php
                foreach($data as $card){
                    $main_img = $card["mainimage"];
                    $title = $card["title"];
                    $adress = $card["adress"] . ", " . $card["citiename"];
                    $price = $card["price"];
                    $id = $card["id"];
                    ?>
                    <div class="shadow card m-2">
                        <img class="card-img-top" src="<?= $fhu->get_cdn_user_img_dir() . "/" . $main_img . ".jpg"; ?>" alt="Hoofdfoto">
                        <div class="card-body col d-flex flex-column justify-content-between">
                            <h5 class="card-title"><?= $title; ?></h5>
                            <div class="card-text"><span class="d-block"><i class="fa-solid fa-location-dot"></i> <?= $adress; ?></span><span class="mt-2 d-block"><i class="fas fa-euro-sign"></i> <?= $price; ?></span></div>
                        </div>
                        <div class="card-footer col d-flex flex-row justify-content-between">
                            <!-- detail page button -->
                            <button onclick="open_details(<?= $id; ?>)" class="btn btn-primary"><i class="fa-solid fa-circle-info"></i> Meer details</button>
                            <!-- edit object button -->
                            <?php if($ulsu->get_login_status() > 0){ ?>
                                <button onclick="edit(<?= $id; ?>)" class="btn btn-primary"><i class="fas fa-edit"></i></button>
                            <?php } ?>
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