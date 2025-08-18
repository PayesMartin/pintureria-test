<?php
session_start();

// Si ya está logueado, redirigir
if (isset($_SESSION['usuario'])) {
    header("Location: index.php"); // Cambia "index.php" por la página principal que tengas
    exit;
}

// Si envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $host = "localhost";
    $dbname = "pintureria";
    $dbuser = "root";
    $dbpass = "";

    $conn = new mysqli($host, $dbuser, $dbpass, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $usuario = trim($_POST['usuario']);
    $contrasena = trim($_POST['contrasena']);

    // Buscar usuario por nombre o email
    $sql = "SELECT * FROM usuarios WHERE usuario = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Verificar contraseña
        if (password_verify($contrasena, $row['contrasena'])) {
            // Guardar sesión
            $_SESSION['usuario'] = $row['usuario'];
            $_SESSION['email'] = $row['email'];
            header("Location: index.php"); // Página después del login
            exit;
        } else {
            $error = "❌ Contraseña incorrecta.";
        }
    } else {
        $error = "❌ Usuario no encontrado.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="content">
    <h1>Iniciar Sesión</h1>

    <?php if (!empty($error)) { ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php } ?>

    <form action="" method="POST" style="max-width: 400px; margin: auto;">
        <input type="text" name="usuario" placeholder="Usuario o Email" required style="width: 100%; padding: 10px; margin-bottom: 10px;">
        <input type="password" name="contrasena" placeholder="Contraseña" required style="width: 100%; padding: 10px; margin-bottom: 10px;">
        <input type="submit" value="Ingresar" style="width: 100%; padding: 10px; background-color: #333; color: white; border: none; cursor: pointer;">
    </form>

    <p style="text-align: center; margin-top: 10px;">
        ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
    </p>
</div>

</body>
</html>
