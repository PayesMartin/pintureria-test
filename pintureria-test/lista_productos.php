<?php
include("conexion.php");
// sin usar
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle product update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idproducto'])) {
    $idproducto = $_POST['idproducto'];
    $description = $_POST['descripcion'];
    $idcategoria = $_POST['idcategorias'];
    $idlinea = $_POST['idlinea'];
    $price = $_POST['precio'];

    $sql = "UPDATE productos SET descripcion=?, idcategorias=?, idlinea=?, precio=? WHERE idproducto=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siidi", $description, $idcategoria, $idlinea, $price, $idproducto);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Producto actualizado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el producto: ' . $stmt->error]);
    }
    $stmt->close();
    exit; // Stop further execution for AJAX requests
}

// Fetch products for display
$sql = "SELECT p.idproducto, p.descripcion, c.descripcion as categoria, l.descripcion as linea, p.precio, p.idcategorias, p.idlinea
        FROM productos p
        JOIN categorias c ON p.idcategorias = c.idcategorias
        JOIN linea l ON p.idlinea = l.idlinea";
$result = $conn->query($sql);

// Fetch categories and lines for dropdowns
$categorias_result = $conn->query("SELECT idcategorias, descripcion FROM categorias");
$categorias = [];
while ($row = $categorias_result->fetch_assoc()) {
    $categorias[] = $row;
}

$lineas_result = $conn->query("SELECT idlinea, descripcion FROM linea");
$lineas = [];
while ($row = $lineas_result->fetch_assoc()) {
    $lineas[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            color: #333;
        }
        h1 {
            color: #0056b3;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e9e9e9;
        }
        .editable {
            cursor: pointer;
        }
        .editable input[type="text"], .editable input[type="number"], .editable select {
            width: 90%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>
    <h1>Listado de Productos</h1>

    <div id="message" class="message" style="display:none;"></div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Línea</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr data-id='" . $row["idproducto"] . "'>";
                    echo "<td>" . $row["idproducto"] . "</td>";
                    echo "<td class='editable' data-field='descripcion'>" . htmlspecialchars($row["descripcion"]) . "</td>";
                    echo "<td class='editable' data-field='idcategorias' data-current-id='" . $row["idcategorias"] . "'>" . htmlspecialchars($row["categoria"]) . "</td>";
                    echo "<td class='editable' data-field='idlinea' data-current-id='" . $row["idlinea"] . "'>" . htmlspecialchars($row["linea"]) . "</td>";
                    echo "<td class='editable' data-field='precio'>" . htmlspecialchars($row["precio"]) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No hay productos para mostrar</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        const messageDiv = document.getElementById('message');
        const categorias = <?php echo json_encode($categorias); ?>;
        const lineas = <?php echo json_encode($lineas); ?>;

        document.querySelectorAll('.editable').forEach(cell => {
            cell.addEventListener('dblclick', function() {
                if (this.querySelector('input') || this.querySelector('select')) {
                    return; // Already in edit mode
                }

                const originalText = this.innerText;
                const field = this.dataset.field;
                const productId = this.closest('tr').dataset.id;
                let inputElement;

                if (field === 'idcategorias') {
                    inputElement = document.createElement('select');
                    categorias.forEach(cat => {
                        const option = document.createElement('option');
                        option.value = cat.idcategorias;
                        option.innerText = cat.descripcion;
                        if (cat.idcategorias == this.dataset.currentId) {
                            option.selected = true;
                        }
                        inputElement.appendChild(option);
                    });
                } else if (field === 'idlinea') {
                    inputElement = document.createElement('select');
                    lineas.forEach(line => {
                        const option = document.createElement('option');
                        option.value = line.idlinea;
                        option.innerText = line.descripcion;
                        if (line.idlinea == this.dataset.currentId) {
                            option.selected = true;
                        }
                        inputElement.appendChild(option);
                    });
                } else if (field === 'precio') {
                    inputElement = document.createElement('input');
                    inputElement.type = 'number';
                    inputElement.step = '0.01';
                    inputElement.value = parseFloat(originalText);
                }
                else {
                    inputElement = document.createElement('input');
                    inputElement.type = 'text';
                    inputElement.value = originalText;
                }

                this.innerText = '';
                this.appendChild(inputElement);
                inputElement.focus();

                const saveChanges = () => {
                    let newValue = inputElement.value;
                    const cellToUpdate = this; // Capture 'this' for use in fetch

                    // For dropdowns, update the displayed text to the selected option's text
                    if (field === 'idcategorias') {
                        const selectedOption = inputElement.options[inputElement.selectedIndex];
                        cellToUpdate.innerText = selectedOption.text;
                        cellToUpdate.dataset.currentId = newValue; // Update current ID
                    } else if (field === 'idlinea') {
                        const selectedOption = inputElement.options[inputElement.selectedIndex];
                        cellToUpdate.innerText = selectedOption.text;
                        cellToUpdate.dataset.currentId = newValue; // Update current ID
                    } else {
                        cellToUpdate.innerText = newValue;
                    }

                    // Send update to server
                    const formData = new FormData();
                    formData.append('idproducto', productId);
                    formData.append('descripcion', cellToUpdate.closest('tr').children[1].innerText); // Get current description
                    formData.append('idcategorias', cellToUpdate.closest('tr').children[2].dataset.currentId); // Get current category ID
                    formData.append('idlinea', cellToUpdate.closest('tr').children[3].dataset.currentId);     // Get current line ID
                    formData.append('precio', cellToUpdate.closest('tr').children[4].innerText);             // Get current price

                    // Overwrite the specific field being edited with its new value
                    formData.set(field, newValue);

                    fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showMessage('success', data.message);
                        } else {
                            showMessage('error', data.message);
                            // Revert text if update failed (optional)
                            if (field === 'idcategorias' || field === 'idlinea') {
                                cellToUpdate.innerText = originalText;
                                cellToUpdate.dataset.currentId = this.dataset.currentId; // Revert ID
                            } else {
                                cellToUpdate.innerText = originalText;
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showMessage('error', 'Error de conexión.');
                        // Revert text on connection error
                        if (field === 'idcategorias' || field === 'idlinea') {
                            cellToUpdate.innerText = originalText;
                            cellToUpdate.dataset.currentId = this.dataset.currentId; // Revert ID
                        } else {
                            cellToUpdate.innerText = originalText;
                        }
                    });
                };

                inputElement.addEventListener('blur', saveChanges);
                inputElement.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        saveChanges();
                    }
                });
            });
        });

        function showMessage(type, message) {
            messageDiv.className = `message ${type}`;
            messageDiv.innerText = message;
            messageDiv.style.display = 'block';
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 3000); // Hide after 3 seconds
        }
    </script>
</body>
</html>