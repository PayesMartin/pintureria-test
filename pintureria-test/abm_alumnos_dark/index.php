<?php
// Conexión a la base de datos
$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "alumnos_db";

$conexion = new mysqli($host, $usuario, $contrasena, $base_datos);
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Actualizar datos si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];

    $sql = "UPDATE alumnos SET 
            nombre = ?, 
            apellido = ?, 
            correo = ?, 
            fecha_nacimiento = ?
            WHERE id = ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssi", $nombre, $apellido, $correo, $fecha_nacimiento, $id);
    $stmt->execute();
    $stmt->close();
    echo "<p style='color: green;'>Registro actualizado correctamente.</p>";
}

// Obtener todos los alumnos
$resultado = $conexion->query("SELECT * FROM alumnos");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Alumnos - Editar Datos</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <h2>Listado de Alumnos</h2>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Correo</th>
            <th>Fecha de Nacimiento</th>
            <th>Acción</th>
        </tr>
        <?php while ($fila = $resultado->fetch_assoc()) { ?>
            <tr>
                <form method="POST">
                    <td><input type="text" name="nombre" value="<?php echo htmlspecialchars($fila['nombre']); ?>"></td>
                    <td><input type="text" name="apellido" value="<?php echo htmlspecialchars($fila['apellido']); ?>"></td>
                    <td><input type="email" name="correo" value="<?php echo htmlspecialchars($fila['correo']); ?>"></td>
                    <td><input type="date" name="fecha_nacimiento" value="<?php echo $fila['fecha_nacimiento']; ?>"></td>
                    <td>
                        <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">
                        <button type="submit" name="update">Guardar</button>
                    </td>
                </form>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
