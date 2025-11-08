<?php
// Datos de conexión
$host = "localhost";
$dbname = "pintureria";
$dbuser = "root"; // Cambiar si es necesario
$dbpass = "";

// Conexión
$conn = new mysqli($host, $dbuser, $dbpass, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Recibir datos del formulario
$usuario = trim($_POST['usuario']);
$email = trim($_POST['email']);
$contrasena = trim($_POST['contrasena']);
$rol = 'cliente'; // Rol por defecto

// Validar que no exista el usuario
$sql = "SELECT * FROM usuarios WHERE usuario = ? OR email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $usuario, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "❌ El usuario o email ya están registrados.";
    exit;
}

// Encriptar contraseña
$hash = password_hash($contrasena, PASSWORD_BCRYPT);

// Insertar usuario
$sql = "INSERT INTO usuarios (usuario, email, contrasena, rol) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $usuario, $email, $hash, $rol);

if ($stmt->execute()) {
    echo "✅ Registro exitoso. <a href='login.php'>Iniciar sesión</a>";
} else {
    echo "Error en el registro: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
