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
    <title>Medewerker aanmaken & bewerken | Vrij Wonen</title>
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
    $sc = new staff_controller();
    $note = new notification_util();

    // create staff member
    if(isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["pass"])){
        $admin = 0;
        if(isset($_POST["admin"])){
            $admin = 1;
        }
        $data = array(
            "username" => strip_tags($_POST["username"]),
            "email" => strip_tags($_POST["email"]),
            "password" => strip_tags($_POST["pass"]),
            "admin" => $admin
        );
        try{
            $sc->create($data);
            $note->notify("Voltooid", "De medewerker is succesvol toegevoegd.");
            header('Location: /beheerder/medewerkers-overzicht');
            exit;
        }
        catch (Exception $e) {
            $note->notify("Fout", "Er is een fout opgetreden bij het toevoegen van de medewerker.");
            header('Location: /beheerder/medewerkers-overzicht');
            exit;
        }
    }
    ?>
</head>
<body>
    <?php require_once __DIR__ . "/../header.php"; ?>
    <div class="container mb-5">
        <!-- create object form -->
        <div class="text-center mt-5">
            <h1>Medewerker aanmaken</h1>
        </div> 
        <div class="d-flex justify-content-center align-items-center mt-5">
            <div id="form-container-responsive" class="shadow w-50 p-3 border rounded">
                <form method="post", action="/beheerder/medewerker-aanmaken" enctype="multipart/form-data">
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Gebruikersnaam </label>
                        <input type="text" name="username" class="form-control" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Email adres </label>
                        <input type="email" name="email" class="form-control" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Wachtwoord </label>
                        <input type="password" name="pass" class="form-control" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Beheerder </label>
                        <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="admin" />
                    </div>

                    <hr/>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-plus"></i> Aanmaken</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php
    $logging_util = new logging_util();
    $logging_util->create_log("create_edit_staff.php");
?>