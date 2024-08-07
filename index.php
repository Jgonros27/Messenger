<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/librerias/sweetalert2.min.css">
    <title>Document</title>
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    ?>
</head>
<body>
    <?php
    require_once("Connection.php");
    if (!isset($_SESSION)) {
        session_start();
    }
    if (isset($_REQUEST["logout"])) {
        session_destroy();
        header("Location: index.php");
    }
    if (isset($_SESSION["sendMessage"])) {
        ?>
        <script src="assets/librerias/sweetalert2.all.min.js"></script>
        <script>
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Message sent successfully",
                showConfirmButton: false,
                timer: 2000
            });
        </script>
        <?php
        unset($_SESSION["sendMessage"]);
    }
    ?>
    <script src="assets/librerias/sweetalert2.all.min.js"></script>
    <?php
    if (isset($_REQUEST["delete"])) {
        ?>
        <script>
            Swal.fire({
                position: "center",
                title: "User deleted",
                icon: "success",
                showConfirmButton: false,
                timer: 2000
            });
        </script>
        <?php
    }
    if (isset($_REQUEST["deleteUser"])) {
        $idcontact = $_REQUEST["deleteUser"];
        $conection = (new Connection())->getPdo();
        $response = $conection->prepare("DELETE FROM contacts WHERE idcontact=:idcontact");
        $response->bindParam(':idcontact', $idcontact);
        $response->execute();
        header("Location: index.php?delete=true");
    }
    $usuario = $contrasena = "";
    $errUsuario = $errContrasena = $errAutenticacion = "";
    $nfallos = 0;
    if (isset($_REQUEST["submitLogin"]) || isset($_SESSION["usuario"])) {
        if (!isset($_SESSION["usuario"])) {
            $usuario = $_POST["usuario"];
            $contrasena = $_POST["contrasena"];
            if (empty($usuario)) {
                $errUsuario = "Campo requerido";
                $nfallos++;
            } else {
                $usuario = htmlspecialchars($usuario);
            }
            if (empty($contrasena)) {
                $errContrasena = "Campo requerido";
                $nfallos++;
            } else {
                $contrasena = htmlspecialchars($contrasena);
            }
        }
        if ($nfallos == 0 && !isset($_SESSION["usuario"])) {
            $conection = (new Connection())->getPdo();
            $response = $conection->prepare("SELECT nick, password from users where nick = :nick and password = :password");
            $response->bindParam(':nick', $usuario);
            $response->bindParam(':password', $contrasena);
            $response->execute();
            if ($response->rowCount() > 0) {
                $_SESSION["usuario"] = $usuario;
                echo "<input type='hidden' id='nomUser' value='$usuario'/>";
            } else {
                $errAutenticacion = "Usuario o contraseña incorrectos";
                $nfallos++;
            }
        }
    }
    require_once("assets/header.php");
    ?>
    <main>
        <?php
        if (isset($_SESSION["usuario"])) {
            require_once("agenda.php");
        }
        if ((!isset($_REQUEST["submitLogin"]) || $nfallos > 0) && !isset($_SESSION["usuario"])) {
        ?>
            <div id="login-form" class="container">
                <div class="form-wrapper">
                    <h2>Login</h2>
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                        <div class="form-group">
                            <label for="usuario">User:</label>
                            <input type="text" name="usuario" id="usuario" value="<?= htmlspecialchars($usuario) ?>">
                            <span class="error"><?= $errUsuario ?></span>
                        </div>
                        <div class="form-group">
                            <label for="contrasena">Password:</label>
                            <input type="password" name="contrasena" id="contrasena" value="<?= htmlspecialchars($contrasena) ?>">
                            <span class="error"><?= $errContrasena ?></span>
                        </div>
                        <input class="boton" type="submit" name="submitLogin" value="Login">
                        <span class="error"><?= $errAutenticacion ?></span>
                    </form>
                </div>
            </div>
        <?php
        }
        ?>
    </main>
</body>
</html>
