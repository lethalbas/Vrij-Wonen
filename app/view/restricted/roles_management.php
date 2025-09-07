<?php
session_start();

// Handle authentication BEFORE any HTML output
require_once __DIR__ . "/../../util/dependencies_util.php"; 
$dep = new dependencies_util();
$dep->all_dependencies();
$file_handler_util = new file_handler_util();
$ulsu = new user_login_session_util();

// Check if user has management access (not archived)
if(!$ulsu->has_management_access()){
    header('Location: /forbidden'); 
    exit;
}

// Check if user has permission to manage roles
if(!$ulsu->has_role('api_admin') && !$ulsu->has_role('system_admin') && !$ulsu->has_role('admin')){
    header('Location: /forbidden'); 
    exit;
}

$staff_controller = new staff_controller();
$currentUserId = $ulsu->get_user_id();

// Check if a specific user is being managed
$selectedUserId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
$selectedUser = null;
if ($selectedUserId) {
    $selectedUser = $staff_controller->get_by_id($selectedUserId);
    if (!$selectedUser || !$staff_controller->can_manage_user($currentUserId, $selectedUserId)) {
        $selectedUserId = null;
        $selectedUser = null;
    }
}

// Handle POST requests for role management
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $userId = (int)($_POST['user_id'] ?? 0);
    $roleId = (int)($_POST['role_id'] ?? 0);
    
    if ($action === 'assign_role') {
        if ($staff_controller->can_manage_user($currentUserId, $userId) && 
            $staff_controller->can_assign_role($currentUserId, $roleId)) {
            $success = $staff_controller->assign_role($userId, $roleId, $currentUserId);
            if ($success) {
                $message = "Rol succesvol toegewezen!";
                $messageType = "success";
            } else {
                $message = "Fout bij toewijzen van rol.";
                $messageType = "error";
            }
        } else {
            $message = "Geen toestemming om deze rol toe te wijzen.";
            $messageType = "error";
        }
    } elseif ($action === 'remove_role') {
        if ($staff_controller->can_manage_user($currentUserId, $userId)) {
            $success = $staff_controller->remove_role($userId, $roleId);
            if ($success) {
                $message = "Rol succesvol verwijderd!";
                $messageType = "success";
            } else {
                $message = "Fout bij verwijderen van rol.";
                $messageType = "error";
            }
        } else {
            $message = "Geen toestemming om deze rol te verwijderen.";
            $messageType = "error";
        }
    }
}

// Get manageable users and assignable roles
$manageableUsers = $staff_controller->get_manageable_users($currentUserId);
$assignableRoles = $staff_controller->get_assignable_roles($currentUserId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rollen Beheer | Vrij Wonen</title>
    <link rel="stylesheet" href="<?= $file_handler_util->get_cdn_style_dir(); ?>/main_styles.css">
</head>
<body>
    <?php require_once __DIR__ . "/../header.php"; ?>
    <div class="container mb-5">
        <div class="row">
            <div class="col-12">
                <div class="text-center mt-5 mb-4">
                    <h1>Rollen Beheer</h1>
                    <p class="lead">Beheer rollen en toestemmingen van medewerkers</p>
                    <button type="button" class="btn btn-secondary" onclick="window.location='/beheerder';">← Terug naar Dashboard</button>
                </div>
                
                <?php if (isset($message)): ?>
                    <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($message) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($selectedUser): ?>
                    <!-- Selected User Role Management -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3>Rollen Beheren voor <?= htmlspecialchars($selectedUser['username']) ?></h3>
                            <small class="text-muted"><?= htmlspecialchars($selectedUser['email']) ?></small>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Huidige Rollen:</h5>
                                    <?php 
                                    $selectedUserRoles = $staff_controller->get_user_roles($selectedUserId);
                                    if (empty($selectedUserRoles)): ?>
                                        <p class="text-muted">Geen rollen toegewezen</p>
                                    <?php else: ?>
                                        <?php foreach ($selectedUserRoles as $role): ?>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge bg-primary"><?= htmlspecialchars($role['name']) ?></span>
                                                <form method="POST" style="display: inline;" onsubmit="return confirm('Weet je zeker dat je de rol <?= htmlspecialchars($role['name']) ?> wilt verwijderen?')">
                                                    <input type="hidden" name="action" value="remove_role">
                                                    <input type="hidden" name="user_id" value="<?= $selectedUserId ?>">
                                                    <input type="hidden" name="role_id" value="<?= $role['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <h5>Nieuwe Rol Toewijzen:</h5>
                                    <form method="POST">
                                        <input type="hidden" name="action" value="assign_role">
                                        <input type="hidden" name="user_id" value="<?= $selectedUserId ?>">
                                        <div class="mb-3">
                                            <select class="form-select" name="role_id" required>
                                                <option value="">Selecteer rol...</option>
                                                <?php foreach ($assignableRoles as $role): ?>
                                                    <option value="<?= $role['id'] ?>"><?= htmlspecialchars($role['name']) ?> (Priority: <?= $role['priority'] ?>)</option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Rol Toewijzen</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- General Role Management -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3>Rollen Toewijzen</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" class="row g-3">
                                <input type="hidden" name="action" value="assign_role">
                                <div class="col-md-4">
                                    <label for="user_id" class="form-label">Medewerker</label>
                                    <select class="form-select" name="user_id" id="user_id" required>
                                        <option value="">Selecteer medewerker...</option>
                                        <?php foreach ($manageableUsers as $user): ?>
                                            <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?> (<?= htmlspecialchars($user['email']) ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="role_id" class="form-label">Rol</label>
                                    <select class="form-select" name="role_id" id="role_id" required>
                                        <option value="">Selecteer rol...</option>
                                        <?php foreach ($assignableRoles as $role): ?>
                                            <option value="<?= $role['id'] ?>"><?= htmlspecialchars($role['name']) ?> (Priority: <?= $role['priority'] ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary">Rol Toewijzen</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Current Users and Roles -->
                <div class="card">
                    <div class="card-header">
                        <h3>Huidige Medewerkers en Rollen</h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($manageableUsers)): ?>
                            <p class="text-muted">Geen medewerkers beschikbaar voor beheer.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Medewerker</th>
                                            <th>Email</th>
                                            <th>Huidige Rollen</th>
                                            <th>Acties</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($manageableUsers as $user): ?>
                                            <?php 
                                            $userRoles = $staff_controller->get_user_roles($user['id']);
                                            ?>
                                            <tr>
                                                <td><?= htmlspecialchars($user['username']) ?></td>
                                                <td><?= htmlspecialchars($user['email']) ?></td>
                                                <td>
                                                    <?php if (empty($userRoles)): ?>
                                                        <span class="text-muted">Geen rollen</span>
                                                    <?php else: ?>
                                                        <?php foreach ($userRoles as $role): ?>
                                                            <span class="badge bg-primary me-1"><?= htmlspecialchars($role['name']) ?></span>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($userRoles)): ?>
                                                        <div class="btn-group" role="group">
                                                            <?php foreach ($userRoles as $role): ?>
                                                                <form method="POST" style="display: inline;" onsubmit="return confirm('Weet je zeker dat je de rol <?= htmlspecialchars($role['name']) ?> wilt verwijderen?')">
                                                                    <input type="hidden" name="action" value="remove_role">
                                                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                                    <input type="hidden" name="role_id" value="<?= $role['id'] ?>">
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Verwijder rol <?= htmlspecialchars($role['name']) ?>">
                                                                        <i class="fas fa-times"></i> <?= htmlspecialchars($role['name']) ?>
                                                                    </button>
                                                                </form>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted">Geen acties beschikbaar</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Role Information -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h3>Rol Informatie</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php 
                            $allRoles = $staff_controller->get_all_roles();
                            foreach ($allRoles as $role): 
                            ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($role['name']) ?></h5>
                                            <p class="card-text"><?= htmlspecialchars($role['description']) ?></p>
                                            <small class="text-muted">Priority: <?= $role['priority'] ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
    $logging_util = new logging_util();
    $logging_util->create_log("roles_management.php");
?>
