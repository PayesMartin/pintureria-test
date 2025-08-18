<?php
// Iniciar la sesión
session_start();

// Desestablecer todas las variables de sesión
$_SESSION = array();

// Destruir la cookie de la sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir la sesión
session_destroy();

// Redirigir a la página de inicio de sesión o a la página principal
header("Location: index.php");
exit;
?>