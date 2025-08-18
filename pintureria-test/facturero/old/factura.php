<?php
// factura.php
include 'db.php';

// Validar que el ID sea un entero
$id_factura = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_factura) {
    die("ID de Factura no válido.");
}

// --- OBTENER DATOS ---

// 1. Datos de la Empresa (siempre es una)
$empresa_result = $conn->query("SELECT * FROM empresa LIMIT 1");
$empresa = $empresa_result->fetch_assoc();

// 2. Datos de la Factura y Cliente (Corregir nombres de tablas y columnas)
$stmt = $conn->prepare(
    "SELECT f.*, c.razon_social, c.documento, c.direccion as dir_cliente, c.condicion_iva as cond_iva_cliente 
     FROM factura f 
     JOIN cliente c ON f.cliente_id = c.id 
     WHERE f.id = ?"
);
$stmt->bind_param("i", $id_factura);
$stmt->execute();
$result = $stmt->get_result();
$factura = $result->fetch_assoc();

if (!$factura) {
    die("Factura no encontrada.");
}

// 3. Items de la Factura (Corregir nombres de tablas)
$stmt = $conn->prepare(
    "SELECT i.descripcion, df.cantidad, df.precio_unitario, df.alicuota_iva, df.subtotal, df.iva, df.total as total_item
     FROM detalle_factura df 
     JOIN item i ON df.item_id = i.id 
     WHERE df.factura_id = ?"
);
$stmt->bind_param("i", $id_factura);
$stmt->execute();
$items_result = $stmt->get_result();
$items = $items_result->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura Nro: <?= str_pad($factura['numero'], 8, '0', STR_PAD_LEFT) ?></title>
<style>
    /* Estilos mejorados para un look profesional */
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 12px; color: #333; }
    .container { max-width: 800px; margin: 20px auto; border: 1px solid #ccc; padding: 20px; }
    .header, .footer { text-align: center; }
    .row { display: flex; justify-content: space-between; margin-bottom: 10px; border: 1px solid #eee; padding: 10px; }
    .col-50 { width: 48%; }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .text-left { text-align: left; }
    h1, h2, h3, h4 { margin: 0 0 5px 0; }
    p { margin: 0 0 3px 0; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ddd; padding: 8px; }
    th { background-color: #f2f2f2; }
    .totals { float: right; width: 300px; margin-top: 20px; }
    .totals td { border: 0; }
    .totals .label { font-weight: bold; }
    .barcode { text-align: center; margin-top: 20px; }
    @media print {
        body { margin: 0; border: 0; }
        .container { border: 0; box-shadow: none; }
        .no-print { display: none; }
    }
</style>
</head>
<body>
    <div class="container">
        <button class="no-print" onclick="window.print()" style="float: right;">Imprimir</button>
        <div class="header row">
            <div class="col-50 text-left">
                <h2><?= htmlspecialchars($empresa['razon_social']) ?></h2>
                <p>Dirección: <?= htmlspecialchars($empresa['direccion']) ?></p>
                <p>Teléfono: <?= htmlspecialchars($empresa['telefono']) ?></p>
                <p>Condición frente al IVA: <?= htmlspecialchars($empresa['condicion_iva']) ?></p>
            </div>
            <div class="col-50 text-center">
                <h1>FACTURA</h1>
                <p><strong>Tipo: <?= $factura['tipo_comprobante'] ?></strong></p>
                <p>Punto de Venta: <?= str_pad($factura['punto_venta'], 5, '0', STR_PAD_LEFT) ?></p>
                <p>Nro: <?= str_pad($factura['numero'], 8, '0', STR_PAD_LEFT) ?></p>
                <p>Fecha de Emisión: <?= date('d/m/Y', strtotime($factura['fecha'])) ?></p>
                <p>CUIT: <?= htmlspecialchars($empresa['cuit']) ?></p>
            </div>
        </div>

        <div class="client-info row">
            <div class="col-50">
                <p><strong>Cliente:</strong> <?= htmlspecialchars($factura['razon_social']) ?></p>
                <p><strong>Dirección:</strong> <?= htmlspecialchars($factura['dir_cliente']) ?></p>
            </div>
            <div class="col-50">
                <p><strong><?= ($factura['documento'] > 20000000000) ? "CUIT:" : "DNI:" ?></strong> <?= htmlspecialchars($factura['documento']) ?></p>
                <p><strong>Condición IVA:</strong> <?= htmlspecialchars($factura['cond_iva_cliente']) ?></p>
                <p><strong>Condición Venta:</strong> <?= htmlspecialchars($factura['forma_pago']) ?></p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="text-left">Producto / Servicio</th>
                    <th class="text-right">Cantidad</th>
                    <th class="text-right">Precio Unit.</th>
                    <th class="text-right">Subtotal</th>
                    <th class="text-right">IVA (%)</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['descripcion']) ?></td>
                    <td class="text-right"><?= $item['cantidad'] ?></td>
                    <td class="text-right">$<?= number_format($item['precio_unitario'], 2, ',', '.') ?></td>
                    <td class="text-right">$<?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                    <td class="text-right"><?= number_format($item['alicuota_iva'], 2, ',', '.') ?>%</td>
                    <td class="text-right">$<?= number_format($item['total_item'], 2, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="totals">
            <table style="border:0;">
                <tr>
                    <td class="label">Subtotal:</td>
                    <td class="text-right">$<?= number_format($factura['subtotal'], 2, ',', '.') ?></td>
                </tr>
                <tr>
                    <td class="label">IVA:</td>
                    <td class="text-right">$<?= number_format($factura['iva_total'], 2, ',', '.') ?></td>
                </tr>
                <tr>
                    <td class="label" style="font-size: 1.2em;">Importe Total:</td>
                    <td class="text-right" style="font-size: 1.2em; font-weight: bold;">$<?= number_format($factura['total'], 2, ',', '.') ?></td>
                </tr>
            </table>
        </div>
        
        <div class="footer">
            <p>Comprobante no válido como factura. Documento sin validez fiscal.</p>
        </div>

    </div>
    
</body>
</html>