<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in | Vrij Wonen</title>
    <?php 
    require_once __DIR__ . "/../util/dependencies_util.php"; 
    $dep = new dependencies_util();
    $dep->all_dependencies();
    $file_handler_util = new file_handler_util();
    ?>
    <script src="<?= $file_handler_util->get_cdn_script_dir(); ?>/log_in.js"></script>
</head>
<body>
    <?php require_once "header.php"; ?>
    <div class="container mb-5">
        <div class="text-center mt-5">
            <h1>Inloggen als beheerder</h1>
        </div> 
        <div class="d-flex justify-content-center align-items-center mt-5">
            <div id="form-container-responsive" class="shadow w-50 p-3 border rounded">
                <form method="post", action="/log-in">
                    <!-- Username input -->
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Gebruikersnaam</label>
                        <input type="text" name="user" class="form-control" />
                    </div>

                    <!-- Password input -->
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example2">Wachtwoord</label>
                        <input type="password" name="pass" class="form-control" />
                    </div>

                    <hr/>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-sign-in-alt"></i> Aanmelden</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php
    $logging_util = new logging_util();
    $logging_util->create_log("log_in.php");
?>