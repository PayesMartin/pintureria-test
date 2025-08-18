<?php
// guardar_factura.php
include 'db.php';

// Leer datos del POST
$nombre_cliente = $_POST['nombre']; // Viene del input 'nombre'
$documento = $_POST['documento'];
$direccion = $_POST['direccion'];
$condicion_iva_cliente = $_POST['condicion_iva'];
$items = json_decode($_POST['items'], true);

// Iniciar una transacción para asegurar la integridad de los datos
$conn->begin_transaction();

try {
    // 1. Verificar si el cliente ya existe, si no, crearlo.
    $stmt = $conn->prepare("SELECT id FROM cliente WHERE documento = ?");
    $stmt->bind_param("s", $documento);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $cliente = $result->fetch_assoc();
        $cliente_id = $cliente['id'];
    } else {
        // Usamos el 'nombre' del formulario para la 'razon_social' de la BD
        $stmt = $conn->prepare("INSERT INTO cliente (razon_social, documento, direccion, condicion_iva) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre_cliente, $documento, $direccion, $condicion_iva_cliente);
        $stmt->execute();
        $cliente_id = $stmt->insert_id;
    }

    // 2. Calcular totales (una sola vez)
    $subtotal_general = 0;
    $iva_total_general = 0;
    
    // Almacenaremos los datos de los items procesados para no volver a consultar la BD
    $items_procesados = [];

    foreach ($items as $item) {
        // Obtenemos los datos del item desde el JSON (ya no consultamos la BD aquí)
        $precio_unitario = $item['precio'];
        $alicuota_iva = $item['iva'];
        $cantidad = $item['cantidad'];
        
        $subtotal_item = $cantidad * $precio_unitario;
        $iva_item = $subtotal_item * ($alicuota_iva / 100);

        $subtotal_general += $subtotal_item;
        $iva_total_general += $iva_item;
        
        $items_procesados[] = [
            'id' => $item['id'],
            'cantidad' => $cantidad,
            'precio_unitario' => $precio_unitario,
            'alicuota_iva' => $alicuota_iva,
            'subtotal' => $subtotal_item,
            'iva' => $iva_item,
            'total' => $subtotal_item + $iva_item
        ];
    }
    $total_general = $subtotal_general + $iva_total_general;

    // 3. Insertar la cabecera de la factura
    // Lógica simple para tipo de comprobante y numeración
    $tipo_comprobante = ($condicion_iva_cliente == 'Responsable Inscripto') ? 'A' : 'B';
    $punto_venta = 1; // Debería ser configurable
    
    // TODO: Implementar un sistema de numeración correlativo real
    $numero_factura = rand(1000, 9999); 
    $forma_pago = 'Contado'; // Puede venir del formulario si se agrega

    // Corregido: Nombres de tabla y columnas
    $stmt = $conn->prepare("INSERT INTO factura (tipo_comprobante, punto_venta, numero, cliente_id, forma_pago, subtotal, iva_total, total) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiisddd", $tipo_comprobante, $punto_venta, $numero_factura, $cliente_id, $forma_pago, $subtotal_general, $iva_total_general, $total_general);
    $stmt->execute();
    $factura_id = $stmt->insert_id;

    // 4. Insertar los detalles de la factura
    // Corregido: Nombre de tabla
    $stmt_detalle = $conn->prepare("INSERT INTO detalle_factura (factura_id, item_id, cantidad, precio_unitario, alicuota_iva, subtotal, iva, total) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($items_procesados as $item_proc) {
        $stmt_detalle->bind_param("iiiddddd", 
            $factura_id, 
            $item_proc['id'], 
            $item_proc['cantidad'], 
            $item_proc['precio_unitario'], 
            $item_proc['alicuota_iva'], 
            $item_proc['subtotal'], 
            $item_proc['iva'], 
            $item_proc['total']
        );
        $stmt_detalle->execute();
    }

    // Si todo salió bien, confirmamos los cambios
    $conn->commit();

    // 5. Redirigir a la página de visualización con el ID correcto
    header("Location: factura.php?id=$factura_id");
    exit;

} catch (Exception $e) {
    // Si algo falla, revertimos todos los cambios
    $conn->rollback();
    // Mostrar un error genérico y registrar el error real
    error_log("Error al guardar factura: " . $e->getMessage());
    die("Ocurrió un error al guardar la factura. Por favor, intente de nuevo.");
}
?>