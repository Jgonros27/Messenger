<script>
    let user = document.querySelector("#nomUser").value;
    Swal.fire({
        position: "center",
        title: "Welcome " + user,
        showConfirmButton: false,
        timer: 2000
    });
</script>
<?php

$nick = $_SESSION["usuario"];
$conection = (new Connection())->getPdo();
$response = $conection->prepare("SELECT A.iduser , A.name FROM users A, contacts E WHERE A.iduser = E.idcontact AND E.iduser=(SELECT iduser FROM users WHERE nick = :nick)");
$response->bindParam(':nick', $nick);
$response->execute();
$users = $response->fetchAll();
?>
<div id="agenda" class="container">
    <h4><?= $_SESSION["usuario"] ?> dashboard</h4>
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Name</th>
                <th>Message</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
            for ($i = 0; $i < $response->rowCount(); $i++) {
            ?>
                <tr>
                    <td><?= $users[$i]["iduser"] ?></td>
                    <td><?= $users[$i]["name"] ?></td>
                    <td>
                        <a href="sendMessage.php?sendUserName=<?= $users[$i]['name'] ?>&sendUserId=<?= $users[$i]['iduser'] ?>">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 12L21 2L11 12L21 22L1 12Z" fill="#4CAF50" />
                            </svg>
                        </a>
                    </td>
                    <td>
                        <a href="index.php?deleteUser=<?= $users[$i]['iduser'] ?>">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19 6H5V19H19V6Z" fill="#F44336" />
                                <path d="M14 11L13 12L12 13L11 12L10 11L11 10L12 9L13 10L14 11Z" fill="white" />
                                <path d="M14 11L13 12L12 13L11 12L10 11L11 10L12 9L13 10L14 11Z" fill="white" />
                            </svg>
                        </a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>

    </table>
</div>