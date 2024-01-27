<?php
    session_start();
    require_once("pdo.php");
    require_once("util.php");

    if (!isset($_SESSION["user"])) {
        die("ACCESS DENIED");
    } 

    if (isset($_POST["cancel"])) {
        header("Location: index.php");
        return;
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

    if (isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["email"]) && isset($_POST["headline"]) && isset($_POST["summary"])) {
        if (empty($_POST["first_name"]) || empty($_POST["last_name"]) || empty($_POST["email"]) || empty($_POST["headline"]) || empty($_POST["summary"])) {
            $_SESSION["notify"] = "<p style='color: red'>All fields are required</p>";
            header("Location: edit.php");
            return;
        } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $_SESSION["notify"] = "<p style='color: red'>Email address must contain @</p>";
            header("Location: edit.php");
            return;
        } else {
            $query = "UPDATE profile SET first_name = :fnm, last_name = :lnm, email = :em, headline = :hln, summary = :sm WHERE user_id = :id AND profile_id = :id_p;";
            $insert_p = $pdo -> prepare($query);
            $insert_p -> execute(array(
                ':id' => $_SESSION["user_id"],
                ':id_p' => $_GET["profile_id"],
                ':fnm' => htmlentities($_POST["first_name"]),
                ':lnm' => htmlentities($_POST["last_name"]),
                ':em' => htmlentities($_POST["email"]),
                ':hln' => htmlentities($_POST["headline"]),
                ':sm' => htmlentities($_POST["summary"])
            ));
            $_SESSION["notify"] = "<p style='color: green;'>Profile updated</p>";
            header("Location: index.php");
            return;
        }
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
    <h1>Adding Profile for <?= $_SESSION["user"] ?></h1>
        <?php
            if (isset($_SESSION["notify"])) {
                echo $_SESSION["notify"];
                unset($_SESSION["notify"]);
            }
        ?>
        <form action="" method="post">
            <label for="first_name">
                First Name: <input type="text" name="first_name" id="first_name" size="40" value="<?= $values["first_name"] ?>">
            </label><br>
            <label for="last_name">
                Last Name: <input type="text" name="last_name" id="last_name" size="40" value="<?= $values["last_name"] ?>">
            </label><br>
            <label for="email">
                Email: <input type="text" name="email" id="email" size="30" value="<?= $values["email"] ?>">
            </label><br>
            <label for="headline">
                Headline: <input type="text" name="headline" id="headline" size="30" value="<?= $values["headline"] ?>">
            </label><br>
            <label for="summary" id="label-text">
                Summary: <br><textarea name="summary" id="summary" cols="60" rows="10"><?= $values["summary"] ?></textarea>
            </label><br>
            <button type="submit">Save</button>
            <button type="submit" name="cancel">Cancel</button>
        </form>
    </div>
</body>
</html>