<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/style.css">
    <title>View Message</title>
</head>
<body>
<?php
require_once("Connection.php");
if (!isset($_SESSION)) {
    session_start();
}
require_once("assets/header.php");
$nick = $_SESSION["usuario"];
$conection = (new Connection())->getPdo();
$response = $conection->prepare("SELECT A.`idmessage`,A.`refsender`,A.`date`,A.`time`,A.`subject`,A.`body`,A.`leido`, E.name, E.nick, E.iduser FROM messages A, users E WHERE refrecipient =(SELECT iduser FROM users WHERE nick = :nick) AND A.refsender = E.iduser");
$response->bindParam(':nick', $nick);
$response->execute();
$table = $response->fetchAll();
?>
<main>
    <div class="container">
        <?php
        if (!isset($_REQUEST["i"])) {
            ?>
            <div id="tablaMensajes">
                <div id="mensajes">
                    <div class="mensaje titulo">
                        <h3>Subject</h3>
                        <h3>Sender</h3>
                    </div>
                    <?php
                    for ($i = 0; $i < $response->rowCount(); $i++) {
                        ?>
                        <a href="messages.php?i=<?= $i ?>">
                            <?php
                            if ($table[$i]["leido"] == 1) {
                                ?>
                                <div class="apartado mensaje">
                                    <p><?= htmlspecialchars($table[$i]["subject"]) ?></p>
                                    <p><?= htmlspecialchars($table[$i]["name"]) ?></p>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="apartado mensaje noLeido">
                                    <p><?= htmlspecialchars($table[$i]["subject"]) ?></p>
                                    <p><?= htmlspecialchars($table[$i]["name"]) ?></p>
                                </div>
                                <?php
                            }
                            ?>
                        </a>
                        <?php
                    }
                    ?>
                </div>
            </div>
        <?php
        } else {
            $i = $_REQUEST["i"];
            if ($table[$i]["leido"] == 0) {
                $idmessage = $table[$i]["idmessage"];
                $conection = (new Connection())->getPdo();
                $response = $conection->prepare("UPDATE messages SET leido = 1 WHERE idmessage = :idmessage");
                $response->bindParam(':idmessage', $idmessage);
                $response->execute();
                header("Location: messages.php?i=" . $i);
            }
            $originalSubject = $table[$i]["subject"];
            if (strpos($originalSubject, 'Reply of:') === false) {
                $replySubject = 'Reply of: ' . $originalSubject;
            }else{
                $replySubject = $originalSubject;
            }
            ?>
            <div id="viewMessage" class="view-message">
                <div class="message-header">
                    <p class="date">Date: <?= htmlspecialchars($table[$i]["date"]) ?></p>
                    <p class="time">Time: <?= htmlspecialchars($table[$i]["time"]) ?></p>
                </div>
                <div class="message-subject">
                    <h2><?= htmlspecialchars($table[$i]["subject"]) ?></h2>
                </div>
                <div class="message-body">
                    <p><?= htmlspecialchars($table[$i]["body"]) ?></p>
                </div>
                <div class="reply-section">
                    <h3>Reply to <?= htmlspecialchars($table[$i]["nick"]) ?>:</h3>
                    <form action="sendMessage.php" method="post">
                        <input type="hidden" name="sendUserId" value="<?= htmlspecialchars($table[$i]["iduser"]) ?>">
                        <input type="hidden" name="subject" value="Reply of: <?= htmlspecialchars($replySubject) ?>">
                        <textarea id="text" name="text" rows="4" placeholder="Type your reply here..."></textarea>
                        <input class="boton" type="submit" name="sendMessage" value="Send Reply">
                    </form>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</main>
</body>
</html>
