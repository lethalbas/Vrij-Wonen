<?php
session_start();
require_once __DIR__ . "/../../controller/objects_controller.php"; 
require_once __DIR__ . "/../../controller/properties_controller.php"; 
require_once __DIR__ . "/../../controller/cities_controller.php"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Object aanmaken & bewerken | Vrij Wonen</title>
    <?php 
    require_once __DIR__ . "/../../util/dependencies_util.php"; 
    $dep = new dependencies_util();
    $dep->all_dependencies();
    $file_handler_util = new file_handler_util();
    $note = new notification_util();
    $oc = new objects_controller();
    $ulsu = new user_login_session_util();
    // restricted page
    if($ulsu->get_login_status() < 1){
        header('Location: /forbidden'); 
        exit;
    }

    // handle post data below
    if(isset($_POST["delete_id"])){
        // delete object
        try{
            $oc->delete($_POST["delete_id"]);
            $note->notify("Voltooid", "Het object is succesvol verwijderd.");
        } catch (Throwable $e) { 
            $note->notify("Error", "Fout bij het verwijderen van het object.");
        }
        header('Location: /objecten-overzicht');
        exit;
    }
    else if(isset($_POST["action-create-edit"])){
        // create object
        if($_POST["action-create-edit"] == "create"){
            try{
                $object_data = array(
                    "title" => strip_tags($_POST["title"]),
                    "price" => strip_tags($_POST["price"]),
                    "adress" => strip_tags($_POST["adress"]),
                    "postcode" => strip_tags($_POST["postcode"]),
                    "cityid" => strip_tags($_POST["citie"]),
                    "description" => strip_tags($_POST["description"])
                );
                $object_images = array(
                    "1" => $_FILES["mainimage"],
                    "2" => $_FILES["image2"],
                    "3" => $_FILES["image3"],
                    "4" => $_FILES["image4"],
                    "5" => $_FILES["image5"]
                );
                $object_properties = $_POST["properties"];

                $data_array = array(
                    "object" => $object_data, 
                    "images" => $object_images, 
                    "properties" => $object_properties
                );
                $oc->create($data_array);
                $note->notify("Voltooid", "Het object is succesvol aangemaakt.");
            } catch (Throwable $e) { 
                $note->notify("Error", "Fout bij het aanmaken van het object. ");
            }
            header('Location: /objecten-overzicht');
            exit;
        }
        else{
            // edit object
            try{
                $object_data = array(
                    "title" => strip_tags($_POST["title"]),
                    "price" => strip_tags($_POST["price"]),
                    "adress" => strip_tags($_POST["adress"]),
                    "postcode" => strip_tags($_POST["postcode"]),
                    "cityid" => strip_tags($_POST["citie"]),
                    "description" => $_POST["description"],
                    "sold" => isset($_POST["sold"])
                );
                $object_images = array();
                if($_FILES["mainimage"]["size"] > 0){ $object_images["1"] = $_FILES["mainimage"]; }
                if($_FILES["image2"]["size"] > 0){ $object_images["2"] = $_FILES["image2"]; }
                if($_FILES["image3"]["size"] > 0){ $object_images["3"] = $_FILES["image3"]; }
                if($_FILES["image4"]["size"] > 0){ $object_images["4"] = $_FILES["image4"]; }
                if($_FILES["image5"]["size"] > 0){ $object_images["5"] = $_FILES["image5"]; }
                
                $object_properties = $_POST["properties"];

                $data_array = array(
                    "object" => $object_data, 
                    "images" => $object_images, 
                    "properties" => $object_properties
                );
                $oc->update($_POST["update_id"], $data_array);
                $note->notify("Voltooid", "Het object is succesvol bijgewerkt.");
            } catch (Throwable $e) { 
                $note->notify("Error", "Fout bij het bijwerken van het object. ");
            }
            header('Location: /objecten-overzicht');
            exit;
        }
    }
    ?>
    <script src="<?= $file_handler_util->get_cdn_script_dir(); ?>/create_object.js"></script>
</head>
<body>
    <?php require_once __DIR__ . "/../header.php"; ?>
    <div class="container mb-5">
        <?php if(!isset($_POST["object_id"])){ ?>
        <!-- create object form -->
        <div class="text-center mt-5">
            <h1>Object aanmaken</h1>
        </div> 
        <div class="d-flex justify-content-center align-items-center mt-5">
            <div id="form-container-responsive" class="shadow w-50 p-3 border rounded">
                <form method="post", action="/beheerder/object-aanmaken" enctype="multipart/form-data">
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Titel </label>
                        <input type="text" name="title" class="form-control" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Prijs </label>
                        <input type="number" name="price" class="form-control" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Adres </label>
                        <input type="text" name="adress" class="form-control" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Postcode </label>
                        <input type="text" name="postcode" class="form-control" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example2">Stad </label>
                        <select id="js-citie-single" class="form-select" data-control="select2" name="citie" required>
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

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Object-omschrijving </label>
                        <input type="text" name="description" class="form-control" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Hoofdafbeelding (.jpg)</label>
                        <input type="file" name="mainimage" class="form-control" accept="image/jpeg" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Detail-afbeeldingen (.jpg)</label>
                        <input type="file" name="image2" class="form-control" accept="image/jpeg" required />
                        <input type="file" name="image3" class="form-control mt-1" accept="image/jpeg" required />
                        <input type="file" name="image4" class="form-control mt-1" accept="image/jpeg" required />
                        <input type="file" name="image5" class="form-control mt-1" accept="image/jpeg" required />
                    </div>

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

                    <input type="hidden" name="action-create-edit" value="create" />

                    <hr/>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-plus"></i> Aanmaken</button>
                </form>
            </div>
        </div>
        <?php } else{ 
            $data = $oc->get($_POST["object_id"]);
        ?>
        <!-- update object form -->
        <div class="text-center mt-5">
            <h1>Object bijwerken</h1>
        </div> 
        <div class="d-flex justify-content-center align-items-center mt-5">
            <div id="form-container-responsive" class="shadow w-50 p-3 border rounded">
                <form method="post", action="/beheerder/object-bewerken" enctype="multipart/form-data">
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Titel </label>
                        <input type="text" name="title" class="form-control" value="<?= $data["object"]["title"] ?>" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Prijs </label>
                        <input type="number" name="price" class="form-control" value="<?= $data["object"]["price"] ?>" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Adres </label>
                        <input type="text" name="adress" class="form-control" value="<?= $data["object"]["adress"] ?>" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Postcode </label>
                        <input type="text" name="postcode" class="form-control" value="<?= $data["object"]["postcode"] ?>" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example2">Stad </label>
                        <select id="js-citie-single" class="form-select" data-control="select2" name="citie" required>
                            <option></option>
                            <?php
                            $cc = new cities_controller();
                            $options = $cc->get_all();
                            foreach($options as $option) {
                                if($option["id"] == $data["object"]["cityid"]){
                                    ?> <option value="<?= $option["id"]; ?>" selected><?= $option["citiename"]; ?></option> <?php
                                }
                                else{
                                    ?> <option value="<?= $option["id"]; ?>"><?= $option["citiename"]; ?></option> <?php
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Object-omschrijving </label>
                        <input type="text" name="description" class="form-control" value="<?= $data["object"]["description"] ?>" required />
                    </div>

                    <?php 
                    $image_path = $file_handler_util->get_cdn_user_img_dir() . "/";
                    $image_ext = ".jpg";

                    $mainimage = $image_path . $data["object"]["mainimage"] . $image_ext;
                    $image2 = $image_path . $data["object"]["image2"] . $image_ext;
                    $image3 = $image_path . $data["object"]["image3"] . $image_ext;
                    $image4 = $image_path . $data["object"]["image4"] . $image_ext;
                    $image5 = $image_path . $data["object"]["image5"] . $image_ext;
                    ?>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Hoofdafbeelding (.jpg)</label> 
                        <input type="file" name="mainimage" class="form-control" accept="image/jpeg" />
                        <button type="button" onclick="preview('<?= $mainimage; ?>')" class="btn btn-primary btn-block mt-1">bekijk huidige afbeelding <i class="fa-solid fa-eye"></i></button>
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Detail-afbeeldingen (.jpg)</label>
                        <input type="file" name="image2" class="form-control" accept="image/jpeg" />
                        <button type="button" onclick="preview('<?= $image2; ?>')" class="btn btn-primary btn-block mt-1">bekijk huidige afbeelding <i class="fa-solid fa-eye"></i></button>
                        <input type="file" name="image3" class="form-control mt-2" accept="image/jpeg" />
                        <button type="button" onclick="preview('<?= $image3; ?>')" class="btn btn-primary btn-block mt-1">bekijk huidige afbeelding <i class="fa-solid fa-eye"></i></button>
                        <input type="file" name="image4" class="form-control mt-2" accept="image/jpeg" />
                        <button type="button" onclick="preview('<?= $image4; ?>')" class="btn btn-primary btn-block mt-1">bekijk huidige afbeelding <i class="fa-solid fa-eye"></i></button>
                        <input type="file" name="image5" class="form-control mt-2" accept="image/jpeg" />
                        <button type="button" onclick="preview('<?= $image5; ?>')" class="btn btn-primary btn-block mt-1">bekijk huidige afbeelding <i class="fa-solid fa-eye"></i></button>
                    </div>

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
                        <?php
                            foreach ($data["properties"] as $new_prop){
                                echo "<script>push_select('" . $new_prop["id"] . "');</script>";
                            }
                        ?>
                        <script>commit_select();</script>
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Verkocht </label>
                        <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="sold" />
                    </div>

                    <input type="hidden" name="action-create-edit" value="edit" />
                    <input type="hidden" name="update_id" value="<?= $_POST["object_id"]; ?>">

                    <hr/>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-edit"></i> Bijwerken</button>
                </form>
            </div>
        </div>
        <?php } ?>
    </div>
</body>
</html>

<?php
    $logging_util = new logging_util();
    $logging_util->create_log("create_edit_object.php");
?>