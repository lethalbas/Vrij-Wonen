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
    <?php require_once __DIR__ . "/../util/dependencies_util.php"; ?>
</head>
<body>
    <?php require_once "header.php"; ?>
    <?php 
        $objects_controller = new objects_controller();
        $table_data = $objects_controller->get_all();
        require_once "data_table.php";
    ?>
</body>
</html>