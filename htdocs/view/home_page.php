<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Vrij Wonen</title>
    <?php require_once __DIR__ . "/../util/dependencies_util.php"; ?>
    <link rel="stylesheet" href="/../style/home_page.css">
</head>
<body>
    <?php require_once "header.php"; ?>
    <div class="container">
        <div class="row align-items-center">
            <div class="text-center mt-5">
                <h1>Vrij Wonen</h1>
                <p class="lead">Welkom op de startpagina van vrij wonen!</p>
                <p>Klik hieronder om gratis door onze objecten te browsen.</p>
                <button type="button" class="btn btn-primary" onclick="window.location='/overzicht';">Overzicht</button>
            </div> 
        </div>         
    </div>
</body>
</html>