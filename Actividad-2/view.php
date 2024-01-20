<?php
    session_start();
    require_once("pdo.php");

    if (!isset($_SESSION["user"])) {
        die("ACCESS DENIED");
    } 

    //Verificando existencia.
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

    //Solicitando los valores.
    $query_v = "SELECT * FROM profile WHERE profile_id = :id";
    $query_values = $pdo -> prepare($query_v);
    $query_values -> execute(array(
        ':id' => $_GET["profile_id"]
    ));
    $values = $query_values -> fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Darlin Daniel Arias Méndez</title>
    <link rel="stylesheet" href="Assets/css/styles-general.css">
</head>
<body>
    <div class="container">
        <h1>Profile information</h1>
        <p>First Name: <?= $values["first_name"] ?></p>
        <p>Last Name: <?= $values["last_name"] ?></p>
        <p>Email: <?= $values["email"] ?></p>
        <p>Headline: <br><?= $values["headline"] ?></p>
        <p>Summary: <br><?= $values["summary"] ?></p><br>
        <a href="index.php">Done</a>
    </div>
</body>
</html>