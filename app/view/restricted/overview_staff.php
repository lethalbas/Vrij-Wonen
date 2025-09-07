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

$file_handler_util = new file_handler_util();
$sc = new staff_controller();

// Handle POST requests BEFORE any HTML output
if(isset($_POST["delete_id"])){
    $sc->delete($_POST["delete_id"]);
    $note->notify("Voltooid", "De medewerker is succesvol verwijderd.");
    header('Location: /beheerder/medewerkers-overzicht');
    exit;
}

if(isset($_POST["archive_id"])){
    if($sc->can_archive_user($ulsu->get_user_id(), $_POST["archive_id"])){
        $sc->archive_user($_POST["archive_id"], $ulsu->get_user_id());
        $note->notify("Voltooid", "De medewerker is succesvol gearchiveerd.");
    } else {
        $note->notify("Fout", "Geen toestemming om deze medewerker te archiveren.");
    }
    header('Location: /beheerder/medewerkers-overzicht');
    exit;
}

if(isset($_POST["unarchive_id"])){
    if($sc->can_archive_user($ulsu->get_user_id(), $_POST["unarchive_id"])){
        $sc->unarchive_user($_POST["unarchive_id"], $ulsu->get_user_id());
        $note->notify("Voltooid", "De medewerker is succesvol uit het archief gehaald.");
    } else {
        $note->notify("Fout", "Geen toestemming om deze medewerker uit het archief te halen.");
    }
    header('Location: /beheerder/medewerkers-overzicht');
    exit;
}

// Now load dependencies AFTER all header() calls
require_once __DIR__ . "/../../util/dependencies_util.php"; 
$dep = new dependencies_util();
$dep->all_dependencies();

// Initialize gravatar_util after dependencies are loaded
$gravatar_util = new gravatar_util();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alle medewerkers | Vrij Wonen</title>
    <link rel="stylesheet" href="<?=$file_handler_util->get_cdn_style_dir(); ?>/overview_staff.css">
    <script src="<?= $file_handler_util->get_cdn_script_dir(); ?>/staff_overview.js"></script>
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
                    <th scope="col">Rollen</th>
                    <th scope="col">Beheerder</th>
                    <th scope="col">Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $data = $sc->get_all();
                foreach ($data as $row){
                    $id = $row["id"];
                    $userRoles = $sc->get_user_roles($id);
                    $canManageUser = $sc->can_manage_user($ulsu->get_user_id(), $id);
                    $canArchiveUser = $sc->can_archive_user($ulsu->get_user_id(), $id);
                    
                    // Check if user has admin+ level access
                    $hasAdminAccess = false;
                    foreach ($userRoles as $role) {
                        if (in_array($role['name'], ['admin', 'system_admin', 'api_admin'])) {
                            $hasAdminAccess = true;
                            break;
                        }
                    }
                    ?>
                    <tr>
                        <th scope="row"><img src="<?= $gravatar_util->get_avatar_with_fallback($row["email"]) ?>" alt="Avatar" class="avatar"></th>
                        <td><?= $row["username"]; ?></td>
                        <td><?= $row["email"]; ?></td>
                        <td>
                            <?php if (empty($userRoles)): ?>
                                <span class="text-muted">Geen rollen</span>
                            <?php else: ?>
                                <?php foreach ($userRoles as $role): ?>
                                    <span class="badge bg-primary me-1"><?= htmlspecialchars($role['name']) ?></span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>
                        <td><?php if($hasAdminAccess){ ?> <i class="fa fa-check" aria-hidden="true"></i> <?php }else{echo "-";} ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <?php if ($canManageUser): ?>
                                    <button class="btn btn-sm btn-info" onclick="window.location='/beheerder/rollen-beheer?user_id=<?= $id ?>'" title="Rollen beheren">
                                        <i class="fas fa-user-cog"></i>
                                    </button>
                                <?php endif; ?>
                                <?php 
                                $isArchived = false;
                                foreach ($userRoles as $role) {
                                    if ($role['name'] === 'archived') {
                                        $isArchived = true;
                                        break;
                                    }
                                }
                                ?>
                                <?php if ($canArchiveUser && !$isArchived): ?>
                                    <button class="btn btn-sm btn-warning" onclick="archiveUser(<?= $id; ?>)" title="Archiveren">
                                        <i class="fas fa-archive"></i>
                                    </button>
                                <?php endif; ?>
                                <?php if ($canArchiveUser && $isArchived): ?>
                                    <button class="btn btn-sm btn-success" onclick="unarchiveUser(<?= $id; ?>)" title="Uit archief halen">
                                        <i class="fas fa-box-open"></i>
                                    </button>
                                <?php endif; ?>
                                <?php if($isArchived){ ?>
                                    <button class="btn btn-sm btn-danger btn_trash" onclick="trash(<?= $id; ?>)" title="Verwijderen">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                <?php } ?>
                            </div>
                        </td>
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