// script.js

let items = [];

function agregarItem() {
    const select = document.getElementById("producto");
    const optionSeleccionado = select.options[select.selectedIndex];
    const cantidad = parseInt(document.getElementById("cantidad").value);

    if (!select.value || isNaN(cantidad) || cantidad <= 0) {
        alert("Por favor, seleccione un producto y una cantidad válida.");
        return;
    }

    // Es crucial guardar el ID del item para el backend
    const item = {
        id: select.value,
        descripcion: optionSeleccionado.dataset.descripcion,
        cantidad: cantidad,
        precio: parseFloat(optionSeleccionado.dataset.precio),
        iva: parseFloat(optionSeleccionado.dataset.iva)
    };
    items.push(item);

    mostrarItems();
    calcularTotales();

    document.getElementById("form-item").reset();
}

function mostrarItems() {
    const container = document.getElementById("item-list");
    container.innerHTML = "<h4>Ítems Agregados:</h4>";

    items.forEach((item, index) => {
        const subtotal = item.cantidad * item.precio;
        container.innerHTML += `
            <p>
                ${item.cantidad} x ${item.descripcion} ($${item.precio.toFixed(2)}) = $${subtotal.toFixed(2)}
            </p>
        `;
    });
}

function calcularTotales() {
    let subtotalGeneral = 0;
    let ivaTotal = 0;

    items.forEach(item => {
        const subtotalItem = item.cantidad * item.precio;
        subtotalGeneral += subtotalItem;
        ivaTotal += subtotalItem * (item.iva / 100);
    });

    let total = subtotalGeneral + ivaTotal;

    document.getElementById("subtotal").textContent = `Subtotal: $${subtotalGeneral.toFixed(2)}`;
    document.getElementById("iva").textContent = `IVA: $${ivaTotal.toFixed(2)}`;
    document.getElementById("total").textContent = `Total: $${total.toFixed(2)}`;
}

function generarFactura() {
    // 1. Validar que haya datos de cliente e ítems
    const formCliente = document.getElementById('form-cliente');
    if (!formCliente.reportValidity()) {
        alert("Por favor, complete todos los datos del cliente.");
        return;
    }
    if (items.length === 0) {
        alert("Por favor, agregue al menos un ítem a la factura.");
        return;
    }

    // 2. Crear un formulario dinámico en memoria
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'guardar_factura.php';

    // 3. Agregar los datos del cliente al formulario
    const datosCliente = new FormData(formCliente);
    for (let [key, value] of datosCliente.entries()) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        form.appendChild(input);
    }
    
    // 4. Agregar los ítems como un string JSON
    const itemsInput = document.createElement('input');
    itemsInput.type = 'hidden';
    itemsInput.name = 'items';
    itemsInput.value = JSON.stringify(items);
    form.appendChild(itemsInput);

    // 5. Adjuntar el formulario al body y enviarlo
    document.body.appendChild(form);
    form.submit();
}

