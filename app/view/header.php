<?php
$ulsu = new user_login_session_util();
?>

<nav class="navbar navbar-expand-sm sticky-top navbar-light bg-light shadow-sm border-bottom">
    <div class="container">
        <a class="navbar-brand" href="/">Vrij Wonen</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbar1">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar1">
            <ul class="navbar-nav">
            <li class="nav-item active">
                    <a class="nav-link" href="/">Startpagina</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="/objecten-overzicht">Objecten</a>
                </li>
                <?php if($ulsu->get_login_status() > 0){ ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/beheerder/startpagina">Beheerdersdashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="log_out()">Uitloggen</a>
                    </li>
                <?php } else{ ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/log-in">Log-in</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>