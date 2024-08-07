<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/librerias/sweetalert2.min.css">
    <title>Document</title>
</head>
<body>
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

require_once("Connection.php");
if (!isset($_SESSION)) {
    session_start();
}
require_once("assets/header.php");

$nick = $_SESSION["usuario"];
$conection = (new Connection())->getPdo();
$response = $conection->prepare("SELECT u.nick, u.name, u.iduser FROM users u LEFT JOIN contacts c ON u.iduser = c.idcontact AND c.iduser = (SELECT iduser FROM users WHERE nick = :nick) WHERE c.idcontact IS NULL AND u.iduser <> (SELECT iduser FROM users WHERE nick = :nick2)");
$response->bindParam(':nick', $nick);
$response->bindParam(':nick2', $nick);
$response->execute();
$table = $response->fetchAll();
?>
<main>
    <div class="container">
        <?php
        if (!isset($_REQUEST["i"])){
            ?>
            <div id="tablaNewAccount">
                <div id="newAccount">
                    <div class="accounts titulo">
                        <h3>Nick</h3>
                        <h3>Name</h3>
                    </div>
                    <?php
                    for ($i = 0; $i < $response->rowCount(); $i++) {
                        ?>
                        <a href="newContact.php?i=<?=$i?>" >
                            <div class="apartado ">
                                <p><?=$table[$i]["nick"]?></p>
                                <p><?=$table[$i]["name"]?></p>
                            </div>
                        </a>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <?php
        }else{
            $i = $_REQUEST["i"];
            $idcontact = $table[$i]["iduser"];
            $conection = (new Connection())->getPdo();
            $response = $conection->prepare("INSERT INTO contacts(iduser, idcontact) VALUES ((SELECT iduser FROM users WHERE nick = :nick),:idcontact)");
            $response->bindParam(':nick', $nick);
            $response->bindParam(':idcontact', $idcontact);
            $response->execute();
            header("Location: newContact.php?newContact=true");
        }
        ?>


    </div>
</main>
<?php
if (isset($_REQUEST["newContact"])){
    ?>
    <script src="assets/librerias/sweetalert2.all.min.js"></script>
    <script>
        Swal.fire({
            position: "center",
            title: "User added",
            icon: "success",
            showConfirmButton: false,
            timer: 2000
        });
    </script>
    <?php
}
?>
</body>
</html>