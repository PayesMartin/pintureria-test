<?php
// db_conexion.php

$servidor = "127.0.0.1"; // O "localhost"
$usuario = "root"; // Usuario por defecto de XAMPP
$contrasena = ""; // Contraseña por defecto de XAMPP es vacía
$base_de_datos = "pintureria";

// Crear conexión
$conexion = new mysqli($servidor, $usuario, $contrasena, $base_de_datos);

// Verificar conexión
if ($conexion->connect_error) {
    die("Falló la conexión: " . $conexion->connect_error);
}

// Establecer el charset a UTF-8 para evitar problemas con tildes y caracteres especiales
$conexion->set_charset("utf8mb4");

?>