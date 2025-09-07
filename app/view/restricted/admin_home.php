<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beheren | Vrij Wonen</title>
    <?php 
    require_once __DIR__ . "/../../util/dependencies_util.php"; 
    $dep = new dependencies_util();
    $dep->all_dependencies();
    $ulsu = new user_login_session_util();
    // restricted page - check if user has management access (not archived)
    if(!$ulsu->has_management_access()){
        header('Location: /forbidden'); 
        exit;
    }
    ?>
</head>
<body>
    <?php require_once __DIR__ . "/../header.php"; ?>
    <div class="container mb-5">
        <div class="row align-items-center">
            <div class="text-center mt-5">
                <h1>Beheerdersdashboard</h1>
                <p class="lead">Welkom op het beheerdersdashboard van Vrij Wonen!</p>
                <?php if($ulsu->has_role('admin') || $ulsu->has_role('system_admin') || $ulsu->has_role('api_admin')){
                    ?>
                    <p>Klik hieronder om alle contactaanvragen te bekijken en medewerkers te beheren.</p>
                    <button type="button" class="btn btn-primary" onclick="window.location='/beheerder/contact-aanvragen-overzicht';">Contactaanvragen</button>
                    <button type="button" class="btn btn-primary" onclick="window.location='/beheerder/medewerkers-overzicht';">Medewerkers</button>
                    <?php if($ulsu->has_role('api_admin') || $ulsu->has_role('system_admin') || $ulsu->has_role('admin')){ ?>
                        <button type="button" class="btn btn-primary" onclick="window.location='/beheerder/rollen-beheer';">Rollen Beheer</button>
                    <?php } ?>
                    <?php if($ulsu->has_role('api_admin')){ ?>
                        <button type="button" class="btn btn-primary" onclick="window.location='/beheerder/api-documentatie';">API Documentatie</button>
                    <?php } ?>
                    <?php
                } else {
                    ?>
                    <p>Klik hieronder om alle contactaanvragen te bekijken.</p>
                    <button type="button" class="btn btn-primary" onclick="window.location='/beheerder/contact-aanvragen-overzicht';">Contactaanvragen</button>
                    <?php if($ulsu->has_role('api_admin') || $ulsu->has_role('system_admin') || $ulsu->has_role('admin')){ ?>
                        <button type="button" class="btn btn-primary" onclick="window.location='/beheerder/rollen-beheer';">Rollen Beheer</button>
                    <?php } ?>
                    <?php if($ulsu->has_role('api_admin')){ ?>
                        <button type="button" class="btn btn-primary" onclick="window.location='/beheerder/api-documentatie';">API Documentatie</button>
                    <?php } ?>
                    <?php
                } ?>
            </div> 
        </div>
    </div>
</body>
</html>

<?php
    $logging_util = new logging_util();
    $logging_util->create_log("admin_home.php");
?>