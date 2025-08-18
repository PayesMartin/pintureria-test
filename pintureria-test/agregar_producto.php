<?php
include("conexion.php");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Cargar listas desplegables
function getOptions($conn, $tabla) {
    $result = $conn->query("SELECT * FROM $tabla");
    $options = "";
    while ($row = $result->fetch_assoc()) {
        $options .= "<option value='{$row['id'.$tabla]}'>{$row['descripcion']}</option>";
    }
    return $options;
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $descripcion = trim($_POST["descripcion"]);
    $categoria = $_POST["categoria"];
    $linea = $_POST["linea"];
    $precio = $_POST["precio"];
    $color = $_POST["color"];
    $cantidad = $_POST["cantidad"];
    $lote = $_POST["lote"];

    // Verificar existencia
    $check = $conn->prepare("SELECT * FROM productos WHERE descripcion = ?");
    $check->bind_param("s", $descripcion);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        $msg = "❌ El producto ya existe.";
    } else {
        // Insertar en productos
        $stmt = $conn->prepare("INSERT INTO productos (descripcion, idcategorias, idlinea, precio) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siid", $descripcion, $categoria, $linea, $precio);
        if ($stmt->execute()) {
            $idproducto = $conn->insert_id;

            // Insertar en stock
            $stmt2 = $conn->prepare("INSERT INTO stock (idproducto, idcolor, cantidad, fecha_carga, lote) VALUES (?, ?, ?, NOW(), ?)");
            $stmt2->bind_param("iiii", $idproducto, $color, $cantidad, $lote);
            if ($stmt2->execute()) {
                $msg = "✅ Producto y stock agregados correctamente.";
            } else {
                $msg = "❌ Error al insertar stock.";
            }
        } else {
            $msg = "❌ Error al insertar producto.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2 style="text-align:center">Agregar Nuevo Producto</h2>
<form method="POST">
    <label>Descripción del producto:</label>
    <input type="text" name="descripcion" required>

    <label>Categoría:</label>
    <select name="categoria" required>
        <?= getOptions($conn, 'categorias'); ?>
    </select>

    <label>Línea:</label>
    <select name="linea" required>
        <?= getOptions($conn, 'linea'); ?>
    </select>

    <label>Precio:</label>
    <input type="number" step="0.01" name="precio" required>

    <label>Color:</label>
    <select name="color" required>
        <?= getOptions($conn, 'color'); ?>
    </select>

    <label>Cantidad inicial:</label>
    <input type="number" name="cantidad" required>

    <label>Número de lote:</label>
    <input type="number" name="lote" required>

    <button type="submit">Agregar Producto</button>
    <?php if ($msg): ?>
        <div class="mensaje <?= strpos($msg, '✅') !== false ? 'success' : '' ?>"><?= $msg ?></div>
    <?php endif; ?>
</form>

</body>
</html>
