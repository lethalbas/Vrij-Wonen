<?php
session_start();
require_once __DIR__ . "/../../controller/inquiries_controller.php"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alle aanvragen | Vrij Wonen</title>
    <?php 
    require_once __DIR__ . "/../../util/dependencies_util.php"; 
    $dep = new dependencies_util();
    $dep->all_dependencies();
    $ulsu = new user_login_session_util();
    // restricted page
    if($ulsu->get_login_status() < 1){
        header('Location: /forbidden'); 
        exit;
    }
    $file_handler_util = new file_handler_util();
    $gravatar_util = new gravatar_util();
    $note = new notification_util();
    $ic = new inquiries_controller();
    if(isset($_POST["complete_id"])){
        $ic->complete($_POST["complete_id"]);
        $note->notify("Voltooid", "De aanvraag is succesvol voltooid.");
        header('Location: /beheerder/contact-aanvragen-overzicht');
        exit;
    }
    ?>
    <link rel="stylesheet" href="<?=$file_handler_util->get_cdn_style_dir(); ?>/overview_inquiries.css">
    <script src="<?= $file_handler_util->get_cdn_script_dir(); ?>/inquiries_overview.js"></script>
</head>
<body>
    <?php require_once __DIR__ . "/../header.php"; ?>
    <div class="container mb-5">
        <div class="row align-items-center">
            <div class="text-center mt-5">
                <h1>Alle contactaanvragen</h1>
                <?php 
                $data = $ic->get_all();
                if (count($data) > 0){ ?>
                        </div> 
                    </div>
                    <table class="table mt-5">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Betr. object</th>
                                <th scope="col">Contact email</th>
                                <th scope="col">Volledige naam</th>
                                <th scope="col">Status</th>
                                <th scope="col">Acties</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($data as $row){
                            $id = $row["id"];
                            $object = $row["objectid"];
                            $msg = $row["message"];
                            $name = $row["fullname"];
                            $email = $row["replyemail"]; 
                            $status = $row["handled"]; ?>
                            <tr>
                                <th scope="row"><img src="<?= $gravatar_util->get_gravatar_url($email) ?>" alt="Na" class="avatar"></th>
                                <td><a href="#" onclick="object_details('<?= $object; ?>')">refnr: <?= $object; ?></a></td>
                                <td><?= $email; ?></td>
                                <td><?= $name; ?></td>
                                <td><?php if($status=='1'){ echo "voltooid"; }else{echo "open";} ?></td>
                                <td>
                                    <!-- complete inquiry -->
                                    <?php if($status=='0'){ ?> 
                                        <button class="btn btn-sm btn-primary btn_trash" onclick="trash(<?= $id; ?>)"><i class="fa fa-check" aria-hidden="true"></i></button>
                                    <?php } ?>
                                    <button class="btn btn-sm btn-primary btn_trash" onclick="view_details('<?= $msg; ?>', '<?= $name; ?>', '<?= $email; ?>')"><i class="fa-solid fa-eye"></i></button>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php } else{ ?>
                <p class="lead">Er zijn momenteel geen contactaanvragen!</p>
            </div> 
        </div>
        <?php } ?>        
    </div>
</body>
</html>

<?php
    $logging_util = new logging_util();
    $logging_util->create_log("overview_inquiries.php");
?>