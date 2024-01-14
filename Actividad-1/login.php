<?php
    session_start();
    require_once("pdo.php");

    if (isset($_POST["cancel"])) {
        header("Location: index.php");
        return;
    }

    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $query = "SELECT count(*) cuenta FROM users WHERE email = :em AND password = :pw";
        $query_c = $pdo -> prepare($query);
        $query_c -> execute(array(
            ':em' => htmlentities($_POST["email"]),
            ':pw' => htmlentities($_POST["password"])
        ));
        $cuenta = $query_c -> fetch(PDO::FETCH_ASSOC);

        if ($cuenta["cuenta"] < 1) {
            $_SESSION["notify"] = "<p style='color: red;'>Incorrect password</p>";
            header("Location: login.php");
            return;
        } else {
            $query_success = "SELECT * FROM users WHERE email = :em AND password = :pw";
            $success = $pdo -> prepare($query_success);
            $success -> execute(array(
                ':em' => htmlentities($_POST["email"]),
                ':pw' => htmlentities($_POST["password"])
            ));
            $account = $success -> fetch(PDO::FETCH_ASSOC);
            $_SESSION["user"] = $account["name"];
            $_SESSION["user_id"] = $account["user_id"];
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
        <form action="" method="post">
            <h1>Please Log In</h1>
            <?php
                if (isset($_SESSION["notify"])) {
                    echo $_SESSION["notify"];
                    unset($_SESSION["notify"]);
                }
            ?>
            <label for="email">
                Email: <input type="text" name="email" id="email">
            </label><br>
            <label for="password">
                Password: <input type="text" name="password" id="password">
            </label><br>
            <button type="submit" onclick="return doValidate()">Log In</button>
            <button type="submit" name="cancel">Cancel</button>
        </form>
        <script>
            function doValidate() {
                console.log("Validating...");
                try {
                    em = document.getElementById("email").value;
                    pw = document.getElementById("password").value;
                    console.log("Validating email = " + em + "pw = " + pw);
                    if (em == null || em == "" || pw == null || pw == "") {
                        alert("Both fields must be filled out");
                        return false;
                    }

                    if (em.indexOf('@') == -1) {
                        alert("Invalid email address");
                        return false;
                    }
                    
                    return true;
                } catch(e) {
                    return false;
                }
                return false;
            }
        </script>
    </div>
</body>
</html>