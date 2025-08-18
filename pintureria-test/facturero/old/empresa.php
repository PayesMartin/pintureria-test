<?php
include 'db.php';

$sql = "SELECT * FROM empresa LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $empresa = $result->fetch_assoc();
    echo "<h2>{$empresa['razon_social']}</h2>";
    echo "<p>CUIT: {$empresa['cuit']}</p>";
    echo "<p>Dirección: {$empresa['direccion']}</p>";
    echo "<p>Teléfono: {$empresa['telefono']}</p>";
    echo "<p>Condición IVA: {$empresa['condicion_iva']}</p>";
} else {
    echo "<p>No hay datos de empresa.</p>";
}
?>
