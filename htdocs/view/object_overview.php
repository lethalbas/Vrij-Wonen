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
    <div class="container">
        <table class="table table_margin_top">
            <thead>
                <tr>
                    <th scope="col" class="center_table">Titel</th>
                    <th scope="col" class="center_table">Prijs</th>
                    <th scope="col" class="center_table">Adres</th>
                    <th scope="col" class="center_table">Verkoopstatus</th>
                    <th scope="col" class="center_table">Acties</th>
                </tr>
            </thead>
            <?php 
                $objects_controller = new objects_controller();
                $table_data = $objects_controller->get_all();
                require_once "data_table.php";
            ?>
        </table>
    </div>
</body>
</html>

<?php
    $logging_util = new logging_util();
    $logging_util->create_log("object_overview.php");
?>