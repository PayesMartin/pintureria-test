<?php
// crear_producto.php

header('Content-Type: application/json');

require 'conexion.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Método no permitido.';
    echo json_encode($response);
    exit;
}

// --- VALIDACIÓN DE DATOS ---
$descripcion = trim($_POST['descripcion'] ?? '');
$idcategorias = (int)($_POST['idcategorias'] ?? 0);
$idlinea = (int)($_POST['idlinea'] ?? 0);
$precio = (float)($_POST['precio'] ?? 0);

if (empty($descripcion) || $idcategorias <= 0 || $idlinea <= 0 || $precio <= 0) {
    $response['message'] = 'Todos los campos son requeridos y deben ser válidos.';
    echo json_encode($response);
    exit;
}

// --- INSERCIÓN EN LA BASE DE DATOS ---
try {
    $stmt = $conexion->prepare("INSERT INTO productos (descripcion, idcategorias, idlinea, precio) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conexion->error);
    }

    $stmt->bind_param("siid", $descripcion, $idcategorias, $idlinea, $precio);
    
    if ($stmt->execute()) {
        $new_id = $conexion->insert_id;
        $response['success'] = true;
        $response['message'] = 'Producto creado con éxito.';
        $response['new_product_id'] = $new_id;
        $response['new_product_desc'] = $descripcion;
    } else {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }

    $stmt->close();

} catch (Exception $e) {
    $response['message'] = "Error en la base de datos: " . $e->getMessage();
}

$conexion->close();

echo json_encode($response);
?>
