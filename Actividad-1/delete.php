<?php
    session_start();
    require_once("pdo.php");

    if (!isset($_SESSION["user"])) {
        die("ACCESS DENIED");
    } 

    if (!isset($_GET["profile_id"])) {
        $_SESSION["notify"] = "<p style='color: red;'>Missing profile_id</p>";
        header("Location: index.php");
        return;
    }

    //Por si cancela, que se yo, no se decide el loco.
    if (isset($_POST["cancel"])) {
        header("Location: index.php");
        return;
    }

    //Verificando, obviamente es inseguro papi, pero ya que.
    $query_c = "SELECT count(*) profile FROM profile WHERE profile_id = :id";
    $profile_c = $pdo -> prepare($query_c);
    $profile_c -> execute(array(
        ':id' => $_GET["profile_id"]
    ));
    $result_c = $profile_c -> fetch(PDO::FETCH_ASSOC);

    if (!$result_c["profile"] >= 1) {
        $_SESSION["notify"] = "<p style='color: red;'>Could not load profile</p>";
        header("Location: index.php");
        return;
    }

    //Ahora si, solicitando datos del perfil.
    $query_p = "SELECT first_name, last_name FROM profile WHERE profile_id = :id";
    $profile = $pdo -> prepare($query_p);
    $profile -> execute(array(
        ':id' => $_GET["profile_id"]
    ));
    $profile_datos = $profile -> fetch(PDO::FETCH_ASSOC);

    //Eliminando perfil, tambien es inseguro papi.
    if (isset($_POST["delete"])) {
        $query_d = "DELETE FROM profile WHERE profile_id = :id";
        $profile_d = $pdo -> prepare($query_d);
        $profile_d -> execute(array(
            ':id' => $_GET["profile_id"]
        ));
        $_SESSION["notify"] = "<p style='color: green;'>Profile deleted</p>";
        header("Location: index.php");
        return;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Darlin Daniel Arias Méndez</title>
    <link rel="stylesheet" href="Assets/css/styles-login-profile.css">
</head>
<body>
    <div class="container">
        <form action="" method="post">
            <h1>Deleteing Profile</h1>
            <p>First Name: <?= $profile_datos["first_name"] ?></p>
            <p>Last Name: <?= $profile_datos["last_name"] ?></p>
            <button type="submit" name="delete">Delete</button>
            <button type="submit" name="cancel">Cancel</button>
        </form>
    </div>
</body>
</html>