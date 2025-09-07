<?php
session_start();

// Handle login logic BEFORE any HTML output
require_once __DIR__ . "/../util/file_handler_util.php";
require_once __DIR__ . "/../util/user_login_session_util.php";
require_once __DIR__ . "/../util/notification_util.php";

$file_handler_util = new file_handler_util();
$ulsu = new user_login_session_util();
$note = new notification_util();

// Handle logout
if(isset($_POST["logout"])){
    $ulsu->log_out();
    $note->notify("Uitgelogd", "U bent succesvol uitgelogd");
    header('Location: /log-in'); 
    exit;
}

// Handle login
if(isset($_POST["user"]) && isset($_POST["pass"])){
    if($ulsu->login_user($_POST["user"], $_POST["pass"])){
        $note->notify("Ingelogd", "U bent succesvol ingelogd");
        header('Location: /beheerder'); 
        exit;
    }
    else {
        $note->notify("Fout", "Helaas waren de inloggegevens niet correct");
        header('Location: /log-in'); 
        exit;
    }
}

// Load dependencies AFTER login logic
require_once __DIR__ . "/../util/dependencies_util.php"; 
$dep = new dependencies_util();
$dep->all_dependencies();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in | Vrij Wonen</title>
    <script src="<?= $file_handler_util->get_cdn_script_dir(); ?>/log_in.js"></script>
</head>
<body>
    <?php
    require_once "header.php";
    if($ulsu->get_login_status() > 0){
        print_logged_in();
    }
    else{
        print_form();
    }
    ?> 
    <div class="container mb-5"> 
    <?php
    function print_form(){
    ?>
        <div class="text-center mt-5">
            <h1>Inloggen als beheerder</h1>
        </div> 
        <div class="d-flex justify-content-center align-items-center mt-5">
            <div id="form-container-responsive" class="shadow w-50 p-3 border rounded">
                <form method="post", action="/log-in">
                    <!-- Username input -->
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Gebruikersnaam</label>
                        <input type="text" name="user" class="form-control" required />
                    </div>

                    <!-- Password input -->
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example2">Wachtwoord</label>
                        <input type="password" name="pass" class="form-control" required />
                    </div>

                    <hr/>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-sign-in-alt"></i> Aanmelden</button>
                </form>
            </div>
        </div>
    </div>
    <?php 
    }

    function print_logged_in(){?>
        <div class="row align-items-center">
            <div class="text-center mt-5">
                <h1>Ingelogd</h1>
                <p class="lead">U bent al ingelogd!</p>
                <p>Klik hieronder om terug naar de startpagina te gaan.</p>
                <button type="button" class="btn btn-primary" onclick="window.location='/';">Home</button>
            </div> 
        </div>   
        <?php  } ?>
    </div>
</body>
</html>

<?php
    $logging_util = new logging_util();
    $logging_util->create_log("log_in.php");
?>