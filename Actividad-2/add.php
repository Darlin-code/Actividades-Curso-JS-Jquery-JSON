<?php
session_start();
require_once("pdo.php");

if (!isset($_SESSION["user"])) {
    die("ACCESS DENIED");
}

if (isset($_POST["cancel"])) {
    header("Location: index.php");
    return;
}

if (isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["email"]) && isset($_POST["headline"]) && isset($_POST["summary"])) {
    if (empty($_POST["first_name"]) || empty($_POST["last_name"]) || empty($_POST["email"]) || empty($_POST["headline"]) || empty($_POST["summary"])) {
        $_SESSION["notify"] = "<p style='color: red'>All fields are required</p>";
        header("Location: add.php");
        return;
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $_SESSION["notify"] = "<p style='color: red'>Email address must contain @</p>";
        header("Location: add.php");
        return;
    } else {
        $query = "INSERT INTO profile (user_id, first_name, last_name, email, headline, summary) VALUES (:id, :fnm, :lnm, :em, :hln, :sm)";
        $insert_p = $pdo->prepare($query);
        $insert_p->execute(array(
            ':id' => $_SESSION["user_id"],
            ':fnm' => htmlentities($_POST["first_name"]),
            ':lnm' => htmlentities($_POST["last_name"]),
            ':em' => htmlentities($_POST["email"]),
            ':hln' => htmlentities($_POST["headline"]),
            ':sm' => htmlentities($_POST["summary"])
        ));

        $rank = 1;
        for ($i = 1; $i <= 9; $i++) {
            if (!isset($_POST["year" . $i])) continue;
            if (!isset($_POST["desc" . $i])) continue;
            $year = $_POST["year" . $i];
            $desc = $_POST["desc" . $i];

            if (empty($year) || empty($_POST[$desc])) {
                $_SESSION["notify"] = "<p style='color: red'>All fields are required</p>";
                header("Location: add.php");
                return;
            }

            $query_position = $pdo -> prepare("INSERT INTO position (profile_id, rank, year, desciption) VALUES (:pid, :rank, :year, :desc)");
            $query_position -> execute(array(
                ':pid' => $_SESSION["user_id"],
                ':rank' => $rank,
                ':year' => $year,
                ':desc' => $desc
            ));
            $rank++;
        }

        $_SESSION["notify"] = "<p style='color: green;'>Profile added</p>";
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
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
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
                First Name: <input type="text" name="first_name" id="first_name" size="40">
            </label><br>
            <label for="last_name">
                Last Name: <input type="text" name="last_name" id="last_name" size="40">
            </label><br>
            <label for="email">
                Email: <input type="text" name="email" id="email" size="30">
            </label><br>
            <label for="headline">
                Headline: <input type="text" name="headline" id="headline" size="30">
            </label><br>
            <label for="summary" id="label-text">
                Summary: <br><textarea name="summary" id="summary" cols="60" rows="10"></textarea>
            </label><br>
            <label>
                Position: <button type="submit" id="addPos">+</button>
            </label><br>
            <div id="position_fields">

            </div>
            <button type="submit">Add</button>
            <button type="submit" name="cancel">Cancel</button>
        </form>
        <script>
            countPos = 0;

            $(document).ready(function() {
                window.console && console.log('Document ready called');
                $('#addPos').click(function(event) {
                    event.preventDefault();
                    if (countPos >= 9) {
                        alert("Maximum of nine position entries exceeded");
                        return;
                    }
                    countPos++;
                    window.console && console.log("Adding position " + countPos);
                    $('#position_fields').append(
                        '<div id="position' + countPos + '"> \
                        <p>Year: <input type="text" name="year' + countPos + '" value="" /> \
                        <input type="button" value="-" \
                            onclick="$(\'#position' + countPos + '\').remove();return false;"></p> \
                        <textarea name="desc' + countPos + '" rows="8" cols="80"></textarea>\
                        </div>');
                });
            });
        </script>
    </div>
</body>

</html>