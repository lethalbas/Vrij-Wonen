<?php
session_start();
require_once __DIR__ . "/../../controller/staff_controller.php"; 
require_once __DIR__ . "/../../util/file_handler_util.php";
require_once __DIR__ . "/../../util/user_login_session_util.php";
require_once __DIR__ . "/../../util/notification_util.php";

// Initialize utilities first
$ulsu = new user_login_session_util();
$note = new notification_util();

// Check access control BEFORE any HTML output
if(!$ulsu->has_management_access() || (!$ulsu->has_role('admin') && !$ulsu->has_role('system_admin') && !$ulsu->has_role('api_admin'))){
    header('Location: /forbidden'); 
    exit;
}

$sc = new staff_controller();

// Get assignable roles for current user
$assignableRoles = $sc->get_assignable_roles($ulsu->get_user_id());

// Handle POST requests BEFORE any HTML output
if(isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["pass"])){
    // Get selected roles
    $selectedRoles = [];
    if(isset($_POST["roles"]) && is_array($_POST["roles"])){
        $selectedRoles = array_map('intval', $_POST["roles"]);
    }
    
    $data = array(
        "username" => strip_tags($_POST["username"]),
        "email" => strip_tags($_POST["email"]),
        "password" => strip_tags($_POST["pass"]),
        "roles" => $selectedRoles
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

// Now load dependencies AFTER all header() calls
require_once __DIR__ . "/../../util/dependencies_util.php"; 
$dep = new dependencies_util();
$dep->all_dependencies();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medewerker aanmaken & bewerken | Vrij Wonen</title>
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
                        <label class="form-label" for="roles">Rollen</label>
                        <div class="form-check">
                            <?php if (empty($assignableRoles)): ?>
                                <p class="text-muted">Geen rollen beschikbaar voor toewijzing.</p>
                            <?php else: ?>
                                <?php foreach ($assignableRoles as $role): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]" value="<?= $role['id'] ?>" id="role_<?= $role['id'] ?>">
                                        <label class="form-check-label" for="role_<?= $role['id'] ?>">
                                            <?= htmlspecialchars($role['name']) ?> 
                                            <small class="text-muted">(Priority: <?= $role['priority'] ?>)</small>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
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