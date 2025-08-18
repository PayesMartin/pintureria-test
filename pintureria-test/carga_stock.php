<?php
include("conexion.php");


// Procesamiento del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idproducto = isset($_POST["idproducto"]) ? intval($_POST["idproducto"]) : 0;
    $idcolor = isset($_POST["idcolor"]) ? intval($_POST["idcolor"]) : 0;
    $cantidad = isset($_POST["cantidad"]) ? intval($_POST["cantidad"]) : 0;
    $lote = isset($_POST["lote"]) ? trim($_POST["lote"]) : '';


    if ($idproducto > 0 && $idcolor > 0 && $cantidad > 0 && !empty($lote)) {
        $sql = "INSERT INTO stock (idproducto, idcolor, cantidad, lote) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iiis", $idproducto, $idcolor, $cantidad, $lote);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Stock cargado exitosamente.</p>";
        } else {
            echo "<p style='color:red;'>Error al cargar el stock: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        
    }
}


// Obtener productos para el selector
$productos = $mysqli->query("SELECT idproducto, descripcion FROM productos ORDER BY descripcion ASC");
// Obtener colores para el selector
$colores = $mysqli->query("SELECT idcolor, descripcion FROM color ORDER BY descripcion ASC");

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cargar Stock</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <h2>Cargar Stock de Producto</h2>
    <form method="post" action="">
        <label for="idproducto">Producto:</label>
        <select name="idproducto" required>
            <option value="">Seleccione un producto</option>
            <?php while ($row = $productos->fetch_assoc()): ?>
                <option value="<?= $row['idproducto'] ?>"><?= htmlspecialchars($row['descripcion']) ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label for="idcolor">Color:</label>
        <select name="idcolor" required>
            <option value="">Seleccione un color</option>
            <?php while ($color = $colores->fetch_assoc()): ?>
                <option value="<?= $color['idcolor'] ?>"><?= htmlspecialchars($color['descripcion']) ?></option>
            <?php endwhile; ?>
        </select><br><br>


        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" min="1" required><br><br>

        <label for="lote">Lote:</label>
        <input type="text" name="lote" id="lote" required><br><br>

        <input type="submit" value="Cargar Stock">
    </form>
</body>
</html>
