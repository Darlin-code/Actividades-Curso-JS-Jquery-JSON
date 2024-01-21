<?php
    session_start();
    require_once("pdo.php");
    require_once("util.php");
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
                <div class="tabla">
                    <?php
                        //Query para el conteo de los perfiles
                        $query_count = "SELECT count(*) perfiles FROM profile WHERE user_id = :id;";
                        $query_c = $pdo -> prepare($query_count);
                        $query_c -> execute(array(
                            ':id' => $_SESSION["user_id"]
                        ));
                        $result_c = $query_c -> fetch(PDO::FETCH_ASSOC);

                        //Query para los perfiles
                        $query_profiles = "SELECT * FROM profile WHERE user_id = :id;";
                        $query_p = $pdo -> prepare($query_profiles);
                        $query_p -> execute(array(
                            ':id' => $_SESSION["user_id"]
                        ));

                        if ($result_c["perfiles"] >= 1) { ?>
                            <table>
                                <tr>
                                    <th>Name</th>
                                    <th>Headline</th>
                                    <th>Action</th>
                                </tr>
                                <?php
                                    while ($result_p = $query_p -> fetch(PDO::FETCH_ASSOC)) { ?>
                                        <tr>
                                            <td><a href="view.php?profile_id=<?=  $result_p["profile_id"] ?>"><?= $result_p["first_name"] . " " . $result_p["last_name"]; ?></a></td>
                                            <td><?= $result_p["headline"] ?></td>
                                            <td><a href="edit.php?profile_id=<?= $result_p["profile_id"] ?>">Edit</a> <a href="delete.php?profile_id=<?= $result_p["profile_id"] ?>">Delete</a></td>
                                        </tr>
                                    <?php }
                                ?>
                            </table>
                        <?php }
                    ?>
                </div>
                <a href="add.php">Add New Entry</a>
                <p><b>Note: </b>Nothing</p>
            </div>
        <?php }
    ?>
</body>
</html>