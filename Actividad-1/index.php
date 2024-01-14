<?php
    session_start();
    require_once("pdo.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Darlin Daniel Arias Méndez</title>
    <link rel="stylesheet" href="assets/css/styles-general.css">
</head>
<body>
    <?php
        if (! isset($_SESSION["user"])) { ?>
            <div class="container">
                <h1>Darlin Daniel Arias Méndez's Resume Registry</h1>
                <a href="login.php">Please log in</a>
                <p><b>Note: </b>Nothing</p>
            </div>
        <?php } else { ?>
            <div class="container">
                <h1>Darlin Daniel Arias Méndez's Resume Registry</h1>
                <?php
                    if (isset($_SESSION["notify"])) {
                        echo $_SESSION["notify"];
                        unset($_SESSION["notify"]);
                    }
                ?>
                <a href="logout.php">Logout</a><br>
                <a href="add.php">Add New Entry</a>
                <p><b>Note: </b>Nothing</p>
            </div>
        <?php }
    ?>
</body>
</html>