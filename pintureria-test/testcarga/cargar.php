<?php
// cargar.php (versión 2 con modal)

// Incluir la conexión a la base de datos para poblar los desplegables
require 'conexion.php';

// Obtener datos para los desplegables
$productos_resultado = $conexion->query("SELECT idproducto, descripcion FROM productos ORDER BY descripcion ASC");
$colores_resultado = $conexion->query("SELECT idcolor, descripcion FROM color ORDER BY descripcion ASC");
$categorias_resultado = $conexion->query("SELECT idcategorias, descripcion FROM categorias ORDER BY descripcion ASC");
$lineas_resultado = $conexion->query("SELECT idlinea, descripcion FROM linea ORDER BY descripcion ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carga de Stock por Remito</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f4f7f9; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .split-screen { display: flex; flex-wrap: wrap; gap: 20px; }
        .viewer { flex: 1; border: 1px dashed #ccc; min-height: 400px; display: flex; align-items: center; justify-content: center; background-color: #fafafa; border-radius: 5px; min-width: 300px; }
        .form-container { flex: 1; min-width: 300px; }
        h1, h2 { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="file"], input[type="number"], select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #3498db; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; transition: background-color 0.3s; }
        button:hover { background-color: #2980b9; }
        #productos-container .producto-row { display: flex; gap: 10px; margin-bottom: 10px; align-items: center; }
        #productos-container .producto-row select { flex: 3; }
        #productos-container .producto-row input { flex: 1; }
        .btn-add, .btn-remove { background-color: #2ecc71; color: white; border: none; padding: 10px; border-radius: 4px; cursor: pointer; }
        .btn-remove { background-color: #e74c3c; }
        .mensaje { padding: 15px; border-radius: 5px; margin-top: 20px; text-align: center; font-weight: bold; }
        .mensaje.exito { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .mensaje.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        
        /* Estilos para la ventana Modal */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); }
        .modal-content { background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .close-button { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
        .close-button:hover, .close-button:focus { color: black; }
    </style>
</head>
<body>

<div class="container">
    <h1>Carga de Stock por Remito</h1>
    <p>Sube el archivo del remito y completa los datos. Si un producto no existe, puedes crearlo desde el desplegable.</p>
    
    <div id="mensaje-respuesta"></div>

    <form id="form-carga" enctype="multipart/form-data">
        <div class="split-screen">
            <div class="form-container">
                <h2>Datos del Documento</h2>
                <div class="form-group">
                    <label for="numero_remito">Número de Remito / Orden de Compra</label>
                    <input type="text" id="numero_remito" name="numero_remito" required>
                </div>
                <div class="form-group">
                    <label for="remitoFile">Archivo del Remito (PDF, JPG, PNG)</label>
                    <input type="file" id="remitoFile" name="remitoFile" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>
                 <div class="viewer" id="viewer">
                    <p>Aquí se mostrará la previsualización del archivo.</p>
                 </div>
            </div>

            <div class="form-container">
                <h2>Productos Recibidos</h2>
                <div id="productos-container">
                    <!-- Las filas de productos se añadirán aquí con JS -->
                </div>
                <button type="button" class="btn-add" id="add-producto">Añadir Producto</button>
            </div>
        </div>
        <hr style="margin: 30px 0;">
        <button type="submit" id="btn-submit">Guardar Carga de Stock</button>
    </form>
</div>

<!-- Ventana Modal para Nuevo Producto -->
<div id="nuevoProductoModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Crear Nuevo Producto</h2>
            <span class="close-button">&times;</span>
        </div>
        <form id="form-nuevo-producto">
            <div class="form-group">
                <label for="nueva_descripcion">Descripción del Producto</label>
                <input type="text" id="nueva_descripcion" name="descripcion" required>
            </div>
            <div class="form-group">
                <label for="nueva_categoria">Categoría</label>
                <select id="nueva_categoria" name="idcategorias" required>
                    <option value="">-- Seleccione --</option>
                    <?php while($cat = $categorias_resultado->fetch_assoc()): ?>
                    <option value="<?= $cat['idcategorias'] ?>"><?= htmlspecialchars($cat['descripcion']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="nueva_linea">Línea</label>
                <select id="nueva_linea" name="idlinea" required>
                    <option value="">-- Seleccione --</option>
                    <?php while($lin = $lineas_resultado->fetch_assoc()): ?>
                    <option value="<?= $lin['idlinea'] ?>"><?= htmlspecialchars($lin['descripcion']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="nuevo_precio">Precio (ej: 15000.00)</label>
                <input type="number" id="nuevo_precio" name="precio" step="0.01" min="0" required>
            </div>
            <button type="submit" id="btn-guardar-producto">Guardar Producto</button>
        </form>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Selectores del DOM
    const productosContainer = document.getElementById('productos-container');
    const addProductoBtn = document.getElementById('add-producto');
    const form = document.getElementById('form-carga');
    const remitoFileInput = document.getElementById('remitoFile');
    const viewer = document.getElementById('viewer');
    const mensajeDiv = document.getElementById('mensaje-respuesta');
    const submitBtn = document.getElementById('btn-submit');

    // Selectores del Modal
    const modal = document.getElementById('nuevoProductoModal');
    const closeModalBtn = document.querySelector('.close-button');
    const formNuevoProducto = document.getElementById('form-nuevo-producto');
    let activeSelectElement = null; // Para saber qué desplegable abrió el modal

    function getProductOptions() {
        // Obtenemos las opciones de los productos desde PHP para reutilizarlas
        let options = '<option value="">-- Seleccione un Producto --</option>';
        options += '<option value="--nuevo--" style="font-weight:bold; color: #3498db;">--- Cargar Nuevo Producto ---</option>';
        <?php
            mysqli_data_seek($productos_resultado, 0); // Reiniciamos el puntero
            while($p = $productos_resultado->fetch_assoc()):
        ?>
        options += `<option value="<?= $p['idproducto'] ?>"><?= htmlspecialchars($p['descripcion']) ?></option>`;
        <?php endwhile; ?>
        return options;
    }

    function getColorOptions() {
        let options = '<option value="">-- Color --</option>';
        <?php
            mysqli_data_seek($colores_resultado, 0); // Reiniciamos el puntero
            while($c = $colores_resultado->fetch_assoc()):
        ?>
        options += `<option value="<?= $c['idcolor'] ?>"><?= htmlspecialchars($c['descripcion']) ?></option>`;
        <?php endwhile; ?>
        return options;
    }

    function addProductoRow() {
        const newRow = document.createElement('div');
        newRow.className = 'producto-row';
        newRow.innerHTML = `
            <select name="productos[id][]" class="select-producto" required>${getProductOptions()}</select>
            <select name="productos[color][]" required>${getColorOptions()}</select>
            <input type="number" name="productos[cantidad][]" placeholder="Cant." min="1" required>
            <button type="button" class="btn-remove">X</button>
        `;
        productosContainer.appendChild(newRow);
    }
    
    addProductoRow(); // Añadir la primera fila al cargar

    addProductoBtn.addEventListener('click', addProductoRow);

    // --- LÓGICA DEL MODAL ---
    productosContainer.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('select-producto') && e.target.value === '--nuevo--') {
            activeSelectElement = e.target; // Guardamos el <select> que disparó el evento
            modal.style.display = 'block';
        }
    });

    closeModalBtn.addEventListener('click', () => modal.style.display = 'none');
    window.addEventListener('click', (e) => {
        if (e.target == modal) {
            modal.style.display = 'none';
        }
    });

    formNuevoProducto.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(formNuevoProducto);
        const submitModalBtn = document.getElementById('btn-guardar-producto');
        submitModalBtn.disabled = true;
        submitModalBtn.textContent = 'Guardando...';

        fetch('crear_producto.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Crear la nueva opción
                const newOption = new Option(data.new_product_desc, data.new_product_id, true, true);
                
                // Añadir la nueva opción a TODOS los desplegables de producto
                document.querySelectorAll('.select-producto').forEach(select => {
                    // Insertamos antes de la opción "--nuevo--"
                    select.insertBefore(newOption.cloneNode(true), select.querySelector('option[value="--nuevo--"]'));
                });

                // Seleccionar el nuevo producto en el desplegable que abrió el modal
                if (activeSelectElement) {
                    activeSelectElement.value = data.new_product_id;
                }

                modal.style.display = 'none';
                formNuevoProducto.reset();
            } else {
                alert('Error al crear el producto: ' + data.message);
            }
        })
        .catch(error => alert('Error de red: ' + error))
        .finally(() => {
            submitModalBtn.disabled = false;
            submitModalBtn.textContent = 'Guardar Producto';
        });
    });

    // --- LÓGICA DEL FORMULARIO PRINCIPAL ---
    productosContainer.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('btn-remove')) {
            e.target.closest('.producto-row').remove();
        }
    });

    remitoFileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            viewer.innerHTML = ''; 
            const objectURL = URL.createObjectURL(file);
            let preview;
            if (file.type === "application/pdf") {
                preview = document.createElement('iframe');
                preview.style.width = '100%';
                preview.style.height = '100%';
            } else {
                preview = document.createElement('img');
                preview.style.maxWidth = '100%';
                preview.style.maxHeight = '100%';
                preview.style.objectFit = 'contain';
            }
            preview.src = objectURL;
            viewer.appendChild(preview);
        }
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        mensajeDiv.innerHTML = '';
        submitBtn.disabled = true;
        submitBtn.textContent = 'Procesando...';

        const formData = new FormData(form);

        fetch('procesar_carga.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mensajeDiv.className = 'mensaje exito';
                mensajeDiv.textContent = data.message;
                form.reset();
                viewer.innerHTML = '<p>Aquí se mostrará la previsualización del archivo.</p>';
                productosContainer.innerHTML = '';
                addProductoRow();
            } else {
                mensajeDiv.className = 'mensaje error';
                mensajeDiv.textContent = 'Error: ' + data.message;
            }
        })
        .catch(error => {
            mensajeDiv.className = 'mensaje error';
            mensajeDiv.textContent = 'Ocurrió un error de red. Por favor, intenta de nuevo. ' + error;
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Guardar Carga de Stock';
        });
    });
});
</script>

</body>
</html>
<?php
// Cerrar la conexión
$conexion->close();
?>
