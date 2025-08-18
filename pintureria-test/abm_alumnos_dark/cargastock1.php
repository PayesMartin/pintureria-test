<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Carga de Stock</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="content">
<<<<<<< HEAD:cargastock.php
  <a href="lista_productos.php">
=======
  <a href="ver_stock.php">
>>>>>>> 116082797d95a665fa707c05d638023815f96d1f:abm_alumnos_dark/cargastock1.php
    <button type="button">Ver Stock Disponible</button>
  </a>

  <h1>Cargar Stock</h1>

  <form action="procesar_stock.php" method="post">
    <label for="producto">Producto</label>
    <input type="text" id="producto" name="producto" required>

    <label for="categoria">Categoría</label>
    <input type="text" id="categoria" name="categoria" required>

    <label for="linea">Línea</label>
    <input type="text" id="linea" name="linea" required>

    <label for="color">Color</label>
    <input type="text" id="color" name="color" required>

    <label for="precio">Precio</label>
    <input type="number" id="precio" name="precio" min="0" step="0.01" required>

    <label for="cantidad">Cantidad</label>
    <input type="number" id="cantidad" name="cantidad" min="1" required>

    <button type="submit">Cargar</button>
  </form>
</div>

</body>
</html>

