<?php
$servidor = "localhost";
$usuario = "root";
$clave = "";
$basededatos = "pintureria";

$conn = new mysqli($servidor, $usuario, $clave, $basededatos);
$mysqli = new mysqli($servidor, $usuario, $clave, $basededatos);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>