<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Ventas - Pinturería</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f0f0f0;
        }

        /* Navbar */
        .navbar {
            background-color: #333;
            height: 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .navbar h1 {
            color: #e0e0e0;
            font-size: 20px;
        }

        .carrito-badge {
            background-color: #e74c3c;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .carrito-badge:hover {
            background-color: #c0392b;
        }

        /* Container principal */
        .container {
            max-width: 1400px;
            margin: 20px auto;
            padding: 0 20px;
        }

        /* Grid de dos columnas */
        .main-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .card h2 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        /* Búsqueda de productos */
        .search-box {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .search-box input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .search-box select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            min-width: 100px;
        }

        /* Lista de productos */
        .productos-lista {
            max-height: 400px;
            overflow-y: auto;
        }

        .producto-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s;
        }

        .producto-item:hover {
            background-color: #f8f9fa;
        }

        .producto-info {
            flex: 1;
        }

        .producto-nombre {
            font-weight: bold;
            color: #2c3e50;
        }

        .producto-detalles {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 5px;
        }

        .producto-precio {
            font-weight: bold;
            color: #27ae60;
            margin: 0 15px;
        }

        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-success {
            background-color: #27ae60;
            color: white;
        }

        .btn-success:hover {
            background-color: #229954;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
            padding: 5px 10px;
            font-size: 12px;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn-large {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            font-weight: bold;
        }

        /* Carrito */
        .carrito-vacio {
            text-align: center;
            padding: 40px;
            color: #95a5a6;
        }

        .carrito-item {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr auto;
            gap: 10px;
            align-items: center;
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .carrito-item-nombre {
            font-weight: bold;
        }

        .cantidad-control {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .cantidad-control input {
            width: 50px;
            text-align: center;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .carrito-total {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #3498db;
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }

        /* Formulario de cliente */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #2c3e50;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow-y: auto;
        }

        .modal-content {
            background-color: white;
            margin: 50px auto;
            padding: 30px;
            width: 90%;
            max-width: 800px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        .close {
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #95a5a6;
        }

        .close:hover {
            color: #2c3e50;
        }

        /* Factura */
        .factura {
            background: white;
            padding: 40px;
            max-width: 800px;
            margin: 0 auto;
        }

        .factura-header {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 20px;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .factura-empresa {
            font-size: 12px;
        }

        .factura-empresa h3 {
            margin-bottom: 10px;
        }

        .factura-tipo {
            text-align: center;
            border: 3px solid #333;
            padding: 10px;
            min-width: 100px;
        }

        .factura-tipo h1 {
            font-size: 60px;
            margin: 10px 0;
        }

        .factura-numero {
            text-align: right;
            font-size: 12px;
        }

        .factura-cliente {
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }

        .factura-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .factura-table th,
        .factura-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .factura-table th {
            background-color: #333;
            color: white;
        }

        .factura-table tfoot td {
            font-weight: bold;
            background-color: #f8f9fa;
        }

        .factura-table .text-right {
            text-align: right;
        }

        .factura-footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            .factura, .factura * {
                visibility: visible;
            }
            .factura {
                position: absolute;
                left: 0;
                top: 0;
            }
            .btn {
                display: none;
            }
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <h1>🎨 Sistema de Ventas - Pinturería</h1>
        <div class="carrito-badge" onclick="scrollToCarrito()">
            🛒 Carrito (<span id="carrito-count">0</span>)
        </div>
    </nav>

    <!-- Contenedor principal -->
    <div class="container">
        <div class="main-grid">
            <!-- Panel de productos -->
            <div class="card">
                <h2>Productos Disponibles</h2>
                <div class="search-box">
                    <input type="text" id="search-input" placeholder="Buscar producto...">
                    <select id="categoria-filter">
                        <option value="">Todas las categorías</option>
                        <option value="Hogar">Hogar</option>
                        <option value="Automotor">Automotor</option>
                        <option value="Insumos">Insumos</option>
                    </select>
                </div>
                <div class="productos-lista" id="productos-lista"></div>
            </div>

            <!-- Panel de carrito -->
            <div class="card">
                <h2>Carrito de Compras</h2>
                <div id="carrito-container">
                    <div class="carrito-vacio">
                        <p>El carrito está vacío</p>
                        <p style="font-size: 50px;">🛒</p>
                    </div>
                </div>
                <div id="carrito-total" class="carrito-total hidden"></div>
                <button class="btn btn-success btn-large hidden" id="btn-proceder" onclick="mostrarFormularioCliente()">
                    Proceder a Facturar
                </button>
            </div>
        </div>

        <!-- Formulario de cliente -->
        <div class="card hidden" id="cliente-form-card">
            <h2>Datos del Cliente</h2>
            <form id="cliente-form">
                <div class="form-group">
                    <label>Nombre y Apellido / Razón Social *</label>
                    <input type="text" id="cliente-nombre" required>
                </div>
                <div class="form-group">
                    <label>DNI / CUIT *</label>
                    <input type="text" id="cliente-documento" required>
                </div>
                <div class="form-group">
                    <label>Dirección *</label>
                    <input type="text" id="cliente-direccion" required>
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" id="cliente-telefono">
                </div>
                <div class="form-group">
                    <label>Condición Fiscal *</label>
                    <select id="cliente-condicion" required>
                        <option value="">-- Seleccione --</option>
                        <option value="Responsable Inscripto">Responsable Inscripto</option>
                        <option value="Monotributista">Monotributista</option>
                        <option value="Consumidor Final">Consumidor Final</option>
                        <option value="Exento">Exento</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success btn-large">Generar Factura</button>
            </form>
        </div>
    </div>

    <!-- Modal de Factura -->
    <div id="factura-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Factura Generada</h2>
                <span class="close" onclick="cerrarFactura()">&times;</span>
            </div>
            <div id="factura-content"></div>
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button class="btn btn-primary btn-large" onclick="imprimirFactura()">🖨️ Imprimir</button>
                <button class="btn btn-success btn-large" onclick="nuevaVenta()">✓ Nueva Venta</button>
            </div>
        </div>
    </div>

    <script>
        // Datos de productos demo
        const productosDemo = [
            { id: 1, nombre: 'Tersuave Latex Interior', categoria: 'Hogar', linea: 'Al agua', color: 'Blanco', precio: 15000, stock: 10 },
            { id: 2, nombre: 'Sinteplast Esmalte Sintético', categoria: 'Automotor', linea: 'Sintética', color: 'Negro', precio: 18000, stock: 8 },
            { id: 3, nombre: 'Pintura Acrílica', categoria: 'Hogar', linea: 'Acrílica', color: 'Rojo', precio: 12000, stock: 15 },
            { id: 4, nombre: 'Hidroesmalte Satinado', categoria: 'Hogar', linea: 'Al agua', color: 'Blanco', precio: 26100, stock: 6 },
            { id: 5, nombre: 'Sellador Sinteplast', categoria: 'Insumos', linea: 'Sintética', color: 'Transparente', precio: 6750, stock: 20 },
            { id: 6, nombre: 'Enduido Interior', categoria: 'Insumos', linea: 'Al agua', color: 'Blanco', precio: 6300, stock: 12 },
            { id: 7, nombre: 'Recuplast Interior Mate', categoria: 'Hogar', linea: 'Acrílica', color: 'Blanco', precio: 14000, stock: 10 },
            { id: 8, nombre: 'Recuplast Frentes', categoria: 'Hogar', linea: 'Acrílica', color: 'Varios', precio: 17000, stock: 7 }
        ];

        let carrito = [];
        let numeroFactura = 1000;

        // Renderizar productos
        function renderizarProductos(filtro = '', categoria = '') {
            const lista = document.getElementById('productos-lista');
            const productosFiltrados = productosDemo.filter(p => {
                const coincideNombre = p.nombre.toLowerCase().includes(filtro.toLowerCase());
                const coincideCategoria = categoria === '' || p.categoria === categoria;
                return coincideNombre && coincideCategoria;
            });

            lista.innerHTML = productosFiltrados.map(producto => `
                <div class="producto-item">
                    <div class="producto-info">
                        <div class="producto-nombre">${producto.nombre}</div>
                        <div class="producto-detalles">${producto.categoria} - ${producto.linea} - ${producto.color}</div>
                    </div>
                    <div class="producto-precio">$${producto.precio.toLocaleString('es-AR')}</div>
                    <button class="btn btn-primary" onclick="agregarAlCarrito(${producto.id})">Agregar</button>
                </div>
            `).join('');
        }

        // Agregar al carrito
        function agregarAlCarrito(idProducto) {
            const producto = productosDemo.find(p => p.id === idProducto);
            const itemExistente = carrito.find(item => item.id === idProducto);

            if (itemExistente) {
                itemExistente.cantidad++;
            } else {
                carrito.push({ ...producto, cantidad: 1 });
            }

            renderizarCarrito();
        }

        // Renderizar carrito
        function renderizarCarrito() {
            const container = document.getElementById('carrito-container');
            const totalDiv = document.getElementById('carrito-total');
            const btnProceder = document.getElementById('btn-proceder');
            const count = document.getElementById('carrito-count');

            count.textContent = carrito.reduce((sum, item) => sum + item.cantidad, 0);

            if (carrito.length === 0) {
                container.innerHTML = `
                    <div class="carrito-vacio">
                        <p>El carrito está vacío</p>
                        <p style="font-size: 50px;">🛒</p>
                    </div>
                `;
                totalDiv.classList.add('hidden');
                btnProceder.classList.add('hidden');
                return;
            }

            const subtotal = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);

            container.innerHTML = carrito.map(item => `
                <div class="carrito-item">
                    <div class="carrito-item-nombre">${item.nombre}</div>
                    <div class="cantidad-control">
                        <input type="number" value="${item.cantidad}" min="1" 
                               onchange="actualizarCantidad(${item.id}, this.value)">
                    </div>
                    <div>$${item.precio.toLocaleString('es-AR')}</div>
                    <div>$${(item.precio * item.cantidad).toLocaleString('es-AR')}</div>
                    <button class="btn btn-danger" onclick="eliminarDelCarrito(${item.id})">✕</button>
                </div>
            `).join('');

            totalDiv.innerHTML = `TOTAL: $${subtotal.toLocaleString('es-AR')}`;
            totalDiv.classList.remove('hidden');
            btnProceder.classList.remove('hidden');
        }

        // Actualizar cantidad
        function actualizarCantidad(idProducto, nuevaCantidad) {
            const item = carrito.find(i => i.id === idProducto);
            if (item) {
                item.cantidad = parseInt(nuevaCantidad);
                renderizarCarrito();
            }
        }

        // Eliminar del carrito
        function eliminarDelCarrito(idProducto) {
            carrito = carrito.filter(item => item.id !== idProducto);
            renderizarCarrito();
        }

        // Scroll al carrito
        function scrollToCarrito() {
            document.getElementById('carrito-container').scrollIntoView({ behavior: 'smooth' });
        }

        // Mostrar formulario de cliente
        function mostrarFormularioCliente() {
            document.getElementById('cliente-form-card').classList.remove('hidden');
            document.getElementById('cliente-form-card').scrollIntoView({ behavior: 'smooth' });
        }

        // Generar factura
        document.getElementById('cliente-form').addEventListener('submit', function(e) {
            e.preventDefault();
            generarFactura();
        });

        function generarFactura() {
            const cliente = {
                nombre: document.getElementById('cliente-nombre').value,
                documento: document.getElementById('cliente-documento').value,
                direccion: document.getElementById('cliente-direccion').value,
                telefono: document.getElementById('cliente-telefono').value,
                condicion: document.getElementById('cliente-condicion').value
            };

            const tipoComprobante = cliente.condicion === 'Responsable Inscripto' ? 'A' : 'B';
            const fecha = new Date().toLocaleDateString('es-AR');
            const puntoVenta = '0001';
            const numero = String(numeroFactura++).padStart(8, '0');

            const subtotal = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
            const ivaTotal = tipoComprobante === 'A' ? subtotal * 0.21 : 0;
            const total = tipoComprobante === 'A' ? subtotal + ivaTotal : subtotal * 1.21;

            const facturaHTML = `
                <div class="factura">
                    <div class="factura-header">
                        <div class="factura-empresa">
                            <h3>LA CASA DE LAS PINTURAS</h3>
                            <p><strong>CUIT:</strong> 20-12345678-9</p>
                            <p><strong>Dirección:</strong> Av. Fco de Haro 4249</p>
                            <p><strong>Teléfono:</strong> (376) 123-4567</p>
                            <p><strong>Condición IVA:</strong> Responsable Inscripto</p>
                        </div>
                        <div class="factura-tipo">
                            <h1>${tipoComprobante}</h1>
                            <p>COD. 01</p>
                        </div>
                        <div class="factura-numero">
                            <h3>FACTURA</h3>
                            <p><strong>Punto de Venta:</strong> ${puntoVenta}</p>
                            <p><strong>Nro:</strong> ${numero}</p>
                            <p><strong>Fecha:</strong> ${fecha}</p>
                        </div>
                    </div>

                    <div class="factura-cliente">
                        <p><strong>Cliente:</strong> ${cliente.nombre}</p>
                        <p><strong>${cliente.documento.length > 8 ? 'CUIT' : 'DNI'}:</strong> ${cliente.documento}</p>
                        <p><strong>Dirección:</strong> ${cliente.direccion}</p>
                        ${cliente.telefono ? `<p><strong>Teléfono:</strong> ${cliente.telefono}</p>` : ''}
                        <p><strong>Condición IVA:</strong> ${cliente.condicion}</p>
                        <p><strong>Condición de Venta:</strong> Contado</p>
                    </div>

                    <table class="factura-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th class="text-right">Cantidad</th>
                                <th class="text-right">Precio Unit.</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${carrito.map(item => `
                                <tr>
                                    <td>${item.nombre} - ${item.color}</td>
                                    <td class="text-right">${item.cantidad}</td>
                                    <td class="text-right">$${item.precio.toLocaleString('es-AR')}</td>
                                    <td class="text-right">$${(item.precio * item.cantidad).toLocaleString('es-AR')}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                        <tfoot>
                            ${tipoComprobante === 'A' ? `
                                <tr>
                                    <td colspan="3" class="text-right">Subtotal:</td>
                                    <td class="text-right">$${subtotal.toLocaleString('es-AR')}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right">IVA (21%):</td>
                                    <td class="text-right">$${ivaTotal.toLocaleString('es-AR')}</td>
                                </tr>
                            ` : ''}
                            <tr>
                                <td colspan="3" class="text-right"><strong>TOTAL:</strong></td>
                                <td class="text-right"><strong>$${total.toLocaleString('es-AR')}</strong></td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="factura-footer">
                        <p>CAE: 12345678901234 - Vto CAE: ${new Date(Date.now() + 10*24*60*60*1000).toLocaleDateString('es-AR')}</p>
                        <p>Comprobante autorizado por AFIP</p>
                    </div>
                </div>
            `;

            document.getElementById('factura-content').innerHTML = facturaHTML;
            document.getElementById('factura-modal').style.display = 'block';
        }

        function cerrarFactura() {
            document.getElementById('factura-modal').style.display = 'none';
        }

        function imprimirFactura() {
            window.print();
        }

        function nuevaVenta() {
            carrito = [];
            renderizarCarrito();
            document.getElementById('cliente-form').reset();
            document.getElementById('cliente-form-card').classList.add('hidden');
            cerrarFactura();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Event listeners para búsqueda
        document.getElementById('search-input').addEventListener('input', function(e) {
            const categoria = document.getElementById('categoria-filter').value;
            renderizarProductos(e.target.value, categoria);
        });

        document.getElementById('categoria-filter').addEventListener('change', function(e) {
            const busqueda = document.getElementById('search-input').value;
            renderizarProductos(busqueda, e.target.value);
        });

        // Inicializar
        renderizarProductos();
    </script>

</body>
</html>