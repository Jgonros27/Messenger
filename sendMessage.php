<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/librerias/sweetalert2.min.css">
    <title>Send Message</title>
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

    $errSubject = "";
    $nfallos = 0;
    $sendUserName = $_REQUEST["sendUserName"] ?? '';

    if (isset($_REQUEST["sendMessage"])) {
        $subject = $_POST["subject"];
        $text = htmlspecialchars($_POST["text"]);
        $time = date("H:i:s");
        $date = date("Y-m-d");
        $sendUserId = $_REQUEST["sendUserId"];

        if (empty($subject)) {
            $errSubject = "Campo requerido";
            $nfallos++;
        } else {
            $title = htmlspecialchars($subject);
        }

        if ($nfallos == 0) {
            $nick = $_SESSION["usuario"];

            $conection = (new Connection())->getPdo();
            $response = $conection->prepare("SELECT iduser FROM users WHERE nick = :nick");
            $response->bindParam(':nick', $nick);
            $response->execute();
            $idUser = $response->fetch();

            $response = $conection->prepare("INSERT INTO messages (refsender, refrecipient, date, time, subject, body, leido) VALUES (:refsender, :refrecipient, :date, :time, :subject, :body, 0)");
            $response->bindParam(':refsender', $idUser['iduser']);
            $response->bindParam(':refrecipient', $sendUserId);
            $response->bindParam(':date', $date);
            $response->bindParam(':time', $time);
            $response->bindParam(':subject', $subject);
            $response->bindParam(':body', $text);
            $response->execute();
            $_SESSION["sendMessage"] = "mensaje enviado";
            header("Location: index.php");            
        }
    }
    ?>
    <main>
        <div id="mensaje" class="container">
            <h3>Inbox of <?= htmlspecialchars($_SESSION["usuario"]) ?></h3>
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                <div class="form-row">
                    <div class="form-group">
                        <label for="to">To:</label>
                        <input type="text" name="to" id="to" value="<?= htmlspecialchars($sendUserName) ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="from">From:</label>
                        <input type="text" name="from" id="from" value="<?= htmlspecialchars($_SESSION["usuario"]) ?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="subject">Subject:</label>
                    <input type="text" name="subject" id="subject">
                    <span class="error"><?= htmlspecialchars($errSubject) ?></span>
                </div>
                <div class="form-group">
                    <label for="text">Message:</label>
                    <textarea id="text" name="text" rows="4"></textarea>
                </div>
                <input type="hidden" name="sendUserId" value="<?= htmlspecialchars($_REQUEST["sendUserId"]) ?>">
                <input type="hidden" name="sendUserName" value="<?= htmlspecialchars($sendUserName) ?>">
                <input class="boton" type="submit" name="sendMessage" value="Send">
            </form>
        </div>
    </main>
</body>
</html>
