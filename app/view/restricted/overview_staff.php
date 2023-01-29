<?php
session_start();
require_once __DIR__ . "/../../controller/staff_controller.php"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alle medewerkers | Vrij Wonen</title>
    <?php 
    require_once __DIR__ . "/../../util/dependencies_util.php"; 
    $dep = new dependencies_util();
    $dep->all_dependencies();
    $ulsu = new user_login_session_util();
    // restricted page
    if($ulsu->get_login_status() < 2){
        header('Location: /forbidden'); 
        exit;
    }
    $file_handler_util = new file_handler_util();
    $gravatar_util = new gravatar_util();
    $sc = new staff_controller();
    ?>
    <link rel="stylesheet" href="<?=$file_handler_util->get_cdn_style_dir(); ?>/overview_staff.css"/>
</head>
<body>
    <?php require_once __DIR__ . "/../header.php"; ?>
    <div class="container mb-5">
        <div class="row align-items-center">
            <div class="text-center mt-5">
                <h1>Medewerkers</h1>
                <p class="lead">Alle medewerkers van Vrij Wonen!</p>
                <p>Klik hieronder om een medewerker toe te voegen.</p>
                <button type="button" class="btn btn-primary" onclick="window.location='/beheerder/medewerker-aanmaken';"><i class="fas fa-plus"></i> Medewerker aanmaken</button>
            </div> 
        </div>
        <table class="table mt-5">
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Gebruikersnaam</th>
                    <th scope="col">Email</th>
                    <th scope="col">Beheerder</th>
                    <th scope="col">Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $data = $sc->get_all();
                foreach ($data as $row){
                ?>
                    <tr>
                        <th scope="row"><img src="<?= $gravatar_util->get_gravatar_url($row["email"]) ?>" alt="Na" class="avatar" /></th>
                        <td><?= $row["username"]; ?></td>
                        <td><?= $row["email"]; ?></td>
                        <td><?php if($row["admin"]=='1'){echo "true";}else{echo "false";} ?></td>
                        <td><?php if($row["admin"]=='0'){ ?><button class="btn btn-sm btn-primary" onlick="verwijderen(<?= $row["id"]; ?>)"><i class="fas fa-trash"></i> Verwijderen</button><?php }else{echo "-";} ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
    $logging_util = new logging_util();
    $logging_util->create_log("overview_staff.php");
?>