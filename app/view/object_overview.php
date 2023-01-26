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
</head>
<body>
    <?php require_once "header.php"; ?>
    <div class="container">
        <div class="text-center mt-5">
            <h1>Alle objecten</h1>
        </div> 
        <div class="mt-5">
            <img src="<?php echo($file_handler_util->get_user_img_dir()); ?>/1.jpg" alt="Hoofdfoto" />
        </div>
    </div>
</body>
</html>

<?php
    $logging_util = new logging_util();
    $logging_util->create_log("object_overview.php");
?>