<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Facturero</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>
    <h1>Facturero</h1>

    <section id="empresa">
        <?php include 'empresa.php'; ?>
    </section>

    <section id="cliente">
        <?php include 'cliente.php'; ?>
    </section>

<section id="items">
    <h3>Agregar Ítems</h3>

    <form id="form-item" onsubmit="event.preventDefault(); agregarItem();">
        <label for="producto">Producto o Servicio:</label>
        <select id="producto" name="producto" required>
            <option value="">Seleccione un ítem</option>
            <?php
            include 'db.php';
            $res = $conn->query("SELECT id, descripcion, precio_unitario, alicuota_iva FROM item ORDER BY descripcion");
            while ($row = $res->fetch_assoc()) {
                echo "<option 
                        value='{$row['id']}'
                        data-precio='{$row['precio_unitario']}'
                        data-iva='{$row['alicuota_iva']}'
                        data-descripcion='" . htmlspecialchars($row['descripcion'], ENT_QUOTES) . "'>";
                echo "{$row['descripcion']} - $" . number_format($row['precio_unitario'], 2) . "</option>";
            }
            ?>
        </select>

        <label for="cantidad">Cantidad:</label>
        <input type="number" id="cantidad" name="cantidad" min="1" required>

        <button type="submit">Agregar Ítem</button>
    </form>

    <div id="item-list"></div>
</section>


    <section id="totales">
        <h3>Totales</h3>
        <p id="subtotal">Subtotal: $0.00</p>
        <p id="iva">IVA (21%): $0.00</p>
        <p id="total">Total: $0.00</p>
    </section>

    <button onclick="generarFactura()">Generar Factura</button>

    <section id="factura-final">
        
    </section>
</body>
</html>
