
<form id="form-cliente">
    <h3>Datos del Cliente</h3>
    <input type="text" name="nombre" placeholder="Apellido y Nombre / Razón Social" required>
    <input type="text" name="documento" placeholder="CUIT / CUIL / DNI" required>
    <input type="text" name="direccion" placeholder="Dirección" required>
    <select name="condicion_iva" required>
        <option value="">Seleccione Condición IVA</option>
        <option value="Responsable Inscripto">Responsable Inscripto</option>
        <option value="Monotributista">Monotributista</option>
        <option value="Consumidor Final">Consumidor Final</option>
        <option value="Exento">Exento</option>
    </select>
</form>