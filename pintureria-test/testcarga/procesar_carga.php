<?php
// procesar_carga.php

header('Content-Type: application/json');

// Incluimos la conexión
require 'conexion.php';

// Array para la respuesta JSON
$response = ['success' => false, 'message' => ''];

// --- VALIDACIONES ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Método no permitido.';
    echo json_encode($response);
    exit;
}

if (empty($_POST['numero_remito']) || !isset($_FILES['remitoFile']) || empty($_POST['productos'])) {
    $response['message'] = 'Faltan datos requeridos (número de remito, archivo o productos).';
    echo json_encode($response);
    exit;
}

if ($_FILES['remitoFile']['error'] !== UPLOAD_ERR_OK) {
    $response['message'] = 'Error al subir el archivo.';
    echo json_encode($response);
    exit;
}

// --- PROCESAMIENTO DEL ARCHIVO ---
$upload_dir = 'remitos_escaneados/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Crear un nombre de archivo único para evitar colisiones
$file_extension = pathinfo($_FILES['remitoFile']['name'], PATHINFO_EXTENSION);
$nombre_archivo_guardado = "remito_" . time() . "_" . uniqid() . "." . $file_extension;
$upload_path = $upload_dir . $nombre_archivo_guardado;

if (!move_uploaded_file($_FILES['remitoFile']['tmp_name'], $upload_path)) {
    $response['message'] = 'No se pudo mover el archivo subido al directorio de destino.';
    echo json_encode($response);
    exit;
}


// --- INSERCIÓN EN LA BASE DE DATOS ---

// Iniciar una transacción para asegurar la integridad. O todo se hace, o nada se hace.
$conexion->begin_transaction();

try {
    // 1. Registrar el remito en la tabla 'remitos_cargados'
    $numero_remito = $_POST['numero_remito'];
    
    $stmt_remito = $conexion->prepare("INSERT INTO remitos_cargados (numero_remito, nombre_archivo) VALUES (?, ?)");
    if (!$stmt_remito) {
        throw new Exception("Error al preparar la consulta de remito: " . $conexion->error);
    }
    $stmt_remito->bind_param("ss", $numero_remito, $nombre_archivo_guardado);
    $stmt_remito->execute();

    // Obtener el ID del remito que acabamos de insertar. Este será nuestro "lote".
    $id_remito_lote = $conexion->insert_id;

    if ($id_remito_lote == 0) {
        throw new Exception("No se pudo obtener el ID del remito insertado.");
    }

    // 2. Procesar y cargar cada producto en la tabla 'stock'
    $productos = $_POST['productos'];
    
    $stmt_stock = $conexion->prepare("INSERT INTO stock (idproducto, idcolor, cantidad, lote) VALUES (?, ?, ?, ?)");
    if (!$stmt_stock) {
        throw new Exception("Error al preparar la consulta de stock: " . $conexion->error);
    }

    $num_productos = count($productos['id']);
    for ($i = 0; $i < $num_productos; $i++) {
        $id_producto = (int)$productos['id'][$i];
        $id_color = (int)$productos['color'][$i];
        $cantidad = (int)$productos['cantidad'][$i];

        // Validar que los datos no estén vacíos
        if (empty($id_producto) || empty($id_color) || empty($cantidad)) {
            throw new Exception("Hay una fila de producto con datos incompletos.");
        }
        
        $stmt_stock->bind_param("iiii", $id_producto, $id_color, $cantidad, $id_remito_lote);
        $stmt_stock->execute();
    }
    
    // Si todo fue bien, confirmamos la transacción
    $conexion->commit();

    $response['success'] = true;
    $response['message'] = "Carga del remito '" . htmlspecialchars($numero_remito) . "' completada. Se asignó el Lote/ID de Carga: " . $id_remito_lote;

} catch (Exception $e) {
    // Si algo falló, revertimos todos los cambios
    $conexion->rollback();
    $response['message'] = "Error en la transacción: " . $e->getMessage();
    // Opcional: eliminar el archivo subido si la DB falla
    if (file_exists($upload_path)) {
        unlink($upload_path);
    }
}

// Cerramos las sentencias y la conexión
if (isset($stmt_remito)) $stmt_remito->close();
if (isset($stmt_stock)) $stmt_stock->close();
$conexion->close();

echo json_encode($response);

?>