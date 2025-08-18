<?php
$mysqli = new mysqli("localhost", "root", "", "pintureria");
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

function obtener_o_insertar($mysqli, $tabla, $campo, $valor) {
    $stmt = $mysqli->prepare("SELECT id$tabla FROM $tabla WHERE $campo = ?");
    $stmt->bind_param("s", $valor);
    $stmt->execute();
    $stmt->bind_result($id);
    if ($stmt->fetch()) {
        $stmt->close();
        return $id;
    }
    $stmt->close();

    $stmt = $mysqli->prepare("INSERT INTO $tabla ($campo) VALUES (?)");
    $stmt->bind_param("s", $valor);
    $stmt->execute();
    $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $producto = trim($_POST["producto"]);
    $categoria = trim($_POST["categoria"]);
    $linea = trim($_POST["linea"]);
    $color = trim($_POST["color"]);
    $precio = floatval($_POST["precio"]);
    $cantidad = intval($_POST["cantidad"]);

    if ($producto && $categoria && $linea && $color && $precio > 0 && $cantidad > 0) {
        $idcategoria = obtener_o_insertar($mysqli, "categorias", "descripcion", $categoria);
        $idlinea = obtener_o_insertar($mysqli, "linea", "descripcion", $linea);
        $idcolor = obtener_o_insertar($mysqli, "color", "descripcion", $color);

        // Insertar producto
        $stmt = $mysqli->prepare("INSERT INTO productos (descripcion, idcategorias, idlinea, precio) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siid", $producto, $idcategoria, $idlinea, $precio);
        $stmt->execute();
        $idproducto = $stmt->insert_id;
        $stmt->close();

        // Insertar stock
        $stmt = $mysqli->prepare("INSERT INTO stock (idproducto, idcolor, cantidad, fecha_carga) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iii", $idproducto, $idcolor, $cantidad);
        if ($stmt->execute()) {
            echo "<p style='color:green;'>Stock cargado correctamente.</p>";
        } else {
            echo "<p style='color:red;'>Error al insertar stock: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p style='color:red;'>Todos los campos son obligatorios y deben ser válidos.</p>";
    }
}
?>