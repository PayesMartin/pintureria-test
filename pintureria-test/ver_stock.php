<?php
include("conexion.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Stock de Productos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="marca-de-agua">
    <nav class="navbar">
        <ul class="menu-container">
                
            <li class="menu-item">
                <a href="agregar_producto.php" class="menu-btn">Crear producto</a>
            </li>

            <li class="menu-item">
                <a href="carga_stock.php" class="menu-btn">Carga de stock</a>
            </li>
                
            <!-- <li class="menu-item">
                <a href="ver_stock.php" class="menu-btn">Lista de stock</a>
            </li> -->
        </ul>
    </nav>
   
    <div class="content">
        <h1>Stock actual</h1>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; background: transparent; border-collapse: collapse;">
            <tr style="background-color: #333; color: white;">
                <th>Producto</th>
                <th>Categoría</th>
                <th>Línea</th>
                <th>Color</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Fecha_carga</th>
                <th>Lote</th>
            </tr>
            <?php
            $sql = "SELECT 
                    p.idproducto,
                    p.descripcion AS producto,
                    c.descripcion AS categoria,
                    l.descripcion AS linea,
                    col.descripcion AS color,
                    p.precio,
                    s.cantidad,
                    s.fecha_carga,
                    s.lote
                    FROM stock s
                    INNER JOIN productos p ON p.idproducto = s.idproducto
                    INNER JOIN categorias c ON c.idcategorias = p.idcategorias
                    INNER JOIN linea l ON l.idlinea = p.idlinea
                    INNER JOIN color col ON col.idcolor = s.idcolor
                    ORDER BY p.descripcion";


            $resultado = $conn->query($sql);

            if ($resultado && $resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($fila["producto"]) . "</td>";
                    echo "<td>" . htmlspecialchars($fila["categoria"]) . "</td>";
                    echo "<td>" . htmlspecialchars($fila["linea"]) . "</td>";
                    echo "<td>" . htmlspecialchars($fila["color"]) . "</td>";
                    echo "<td>" . $fila["cantidad"] . "</td>";
                    echo "<td>$" . number_format($fila["precio"], 2, ',', '.') . "</td>";
                    echo "<td>" . htmlspecialchars($fila['fecha_carga']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['lote']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No hay productos en stock.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>