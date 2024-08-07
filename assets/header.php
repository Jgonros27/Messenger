<?php
require_once("Connection.php");
if (!isset($_SESSION)) {
    session_start();
}

$unreadMessagesCount = 0;
if (isset($_SESSION["usuario"])) {
    $nick = $_SESSION["usuario"];
    $conection = (new Connection())->getPdo();
    $response = $conection->prepare("SELECT COUNT(*) AS unreadCount FROM messages WHERE refrecipient = (SELECT iduser FROM users WHERE nick = :nick) AND leido = 0");
    $response->bindParam(':nick', $nick);
    $response->execute();
    $result = $response->fetch();
    $unreadMessagesCount = $result['unreadCount'];
}
?>

<header>
    <h1>MESSENGER</h1>
    <?php if (isset($_SESSION["usuario"])): ?>
        <nav id="menu">
            <div id="enlaces">
                <a href="index.php">
                    <div class="apartado">Contact</div>
                </a>
                <a href="newContact.php">
                    <div class="apartado">New Contact</div>
                </a>
                <a href="messages.php">
                    <div class="apartado">Messages
                        <?php if ($unreadMessagesCount > 0): ?>
                            <span class="notification-badge"><?= $unreadMessagesCount ?></span>
                        <?php endif; ?>
                    </div>
                </a>
                <a href="index.php?logout=true">
                    <div class="apartado logout">Log out</div>
                </a>
            </div>
        </nav>
    <?php endif; ?>
</header>
