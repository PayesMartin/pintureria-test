<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <div class="menu-container">
            <div class="menu-item">
                <a href="login.php" class="menu-btn">Volver</a>
            </div>
        </div>
    </div>

    <div class="content">
        <h1>Registro de Usuario</h1>
        <form action="procesar_registro.php" method="POST" style="max-width: 400px; margin: auto;">
            <input type="text" name="usuario" placeholder="Usuario" required style="width: 100%; padding: 10px; margin-bottom: 10px;">
            <input type="email" name="email" placeholder="Correo Electrónico" required style="width: 100%; padding: 10px; margin-bottom: 10px;">
            <input type="password" name="contrasena" placeholder="Contraseña" required style="width: 100%; padding: 10px; margin-bottom: 10px;">
            <input type="submit" value="Registrarse" style="width: 100%; padding: 10px; background-color: #333; color: white; border: none; cursor: pointer;">
        </form>
    </div>
</body>
</html>
