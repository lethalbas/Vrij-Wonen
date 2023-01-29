<?php
session_start();
require_once __DIR__ . "/../controller/inquiries_controller.php"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactaanvraag indienen | Vrij Wonen</title>
    <?php 
    require_once __DIR__ . "/../util/dependencies_util.php"; 
    $dep = new dependencies_util();
    $dep->all_dependencies();
    $ic = new inquiries_controller();
    $note = new notification_util();
    $file_handler_util = new file_handler_util();
    if(isset($_POST["fullname"]) && isset($_POST["replyemail"]) && isset($_POST["message"]) && isset($_POST["object"])){
        $data = array(
            "fullname" => $_POST["fullname"],
            "replyemail" => $_POST["replyemail"],
            "message" => $_POST["message"],
            "object" => $_POST["object"]
        );
        if($ic->create($data)){
            $note->notify("Voltooid", "Uw contactaanvraag is succesvol ingediend.");
            header('Location: /objecten-overzicht');
            exit;
        }
        else{
            $note->notify("Fout", "Er is een fout opgetreden bij het indienen van uw contactaanvraag.");
            header('Location: /objecten-overzicht');
            exit;
        }
    } else if (!isset($_POST["object"])){
        
        $note->notify("Fout", "Er is een fout opgetreden bij het laden van de contactpagina, probeer het later opnieuw.");
        header('Location: /objecten-overzicht');
        exit;
    }
    ?>
    <script src="<?= $file_handler_util->get_cdn_script_dir(); ?>/create_inquiry.js"></script>
</head>
<body>
    <?php require_once __DIR__ . "/header.php"; ?>
    <div class="container mb-5">
        <!-- create inquiry form -->
        <div class="text-center mt-5">
            <h1>Contactaanvraag indienen</h1>
        </div> 
        <div class="d-flex justify-content-center align-items-center mt-5">
            <div id="form-container-responsive" class="shadow w-50 p-3 border rounded">
                <form method="post", action="/contact-aanvraag-aanmaken" enctype="multipart/form-data">
                    <!-- name -->
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Volledige naam </label>
                        <input type="text" name="fullname" class="form-control" required />
                    </div>

                    <!-- email -->
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Email adres </label>
                        <input type="email" name="replyemail" class="form-control" required />
                    </div>

                    <!-- message -->
                    <div class="form-outline mb-4">
                        <label class="form-label" for="form2Example1">Uw bericht </label>
                        <input type="text" name="message" class="form-control" required />
                    </div>

                    <!-- object id (hidden) -->
                    <input type="hidden" name="object" value="<?= $_POST["object"]; ?>">

                    <hr/>

                    <!-- submit button -->
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-plus"></i> Indienen</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php
    $logging_util = new logging_util();
    $logging_util->create_log("create_inquiry.php");
?>