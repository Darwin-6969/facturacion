<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="content-wrapper p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold"><i class="fas fa-boxes me-2"></i>Entrada de Compras e Inventario</h3>
        <a href="<?= base_url('compras') ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Volver</a>
    </div>

    <div class="row">
        <!-- Panel de Formulario -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="fas fa-truck me-1"></i> 1. Proveedor
                </div>
                <div class="card-body">
                    <select id="id_proveedor" class="form-select">
                        <option value="">Seleccione proveedor...</option>
                        <?php foreach ($proveedores as $prov): ?>
                            <option value="<?= $prov['id_proveedor'] ?>"><?= $prov['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white fw-bold">
                    <i class="fas fa-box me-1"></i> 2. Detalle del Producto
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Buscar Existente o Crear Nuevo</label>
                        <select id="select_producto" class="form-select" onchange="evaluarProductoNuevo(this)">
                            <option value="">-- Seleccione o Crear Nuevo --</option>
                            <option value="NUEVO" class="fw-bold text-success">+ [CREAR NUEVO PRODUCTO]</option>
                            <?php foreach ($productos as $prod): ?>
                                <option value="<?= $prod['id_producto'] ?>" data-nombre="<?= $prod['nombre'] ?>" data-precio="<?= $prod['precio_venta'] ?>">
                                    <?= $prod['nombre'] ?> (Stock: <?= $prod['stock'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Input dinámico si el producto no existe -->
                    <div class="mb-3 d-none" id="box_nuevo_nombre">
                        <label class="form-label text-success fw-bold">Nombre del Nuevo Producto</label>
                        <input type="text" id="nuevo_nombre" class="form-control border-success" placeholder="Ej: Teclado Mecánico RGB">
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Cantidad</label>
                            <input type="number" id="cantidad" class="form-control" value="1" min="1">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Costo Compra ($)</label>
                            <input type="number" id="costo_unitario" class="form-control" step="0.01" value="0.00">
                        </div>
                    </div>

                    <div class="mb-3 d-none" id="box_precio_venta">
                        <label class="form-label text-primary fw-bold">Precio Venta Sugerido ($)</label>
                        <input type="number" id="precio_venta" class="form-control border-primary" step="0.01" value="0.00">
                        <small class="text-muted">Se registrará en la lista de Productos para venta.</small>
                    </div>

                    <button type="button" onclick="agregarItem()" class="btn btn-success w-100 fw-bold">
                        <i class="fas fa-plus me-1"></i> Añadir a la Compra
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla de la Compra -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white fw-bold">
                    <i class="fas fa-list me-1"></i> Resumen de Productos a Recibir
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Estado</th>
                                <th>Producto</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-end">Costo U.</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tbl-items">
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Sin productos agregados</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Total: <span class="fw-bold text-success" id="total_compra">$0.00</span></h3>
                    <button type="button" onclick="guardarCompra()" class="btn btn-primary btn-lg fw-bold">
                        <i class="fas fa-save me-1"></i> Guardar Compra e Inventario
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let items = [];

function evaluarProductoNuevo(select) {
    if (select.value === 'NUEVO') {
        $('#box_nuevo_nombre').removeClass('d-none');
        $('#box_precio_venta').removeClass('d-none');
    } else {
        $('#box_nuevo_nombre').addClass('d-none');
        $('#box_precio_venta').addClass('d-none');
    }
}

function agregarItem() {
    const select = document.getElementById('select_producto');
    const val = select.value;
    
    if (!val) {
        alert('Seleccione un producto o elija crear uno nuevo');
        return;
    }

    let idProducto = 0;
    let nombre = '';
    let esNuevo = false;
    let precioVenta = 0;

    if (val === 'NUEVO') {
        nombre = $('#nuevo_nombre').val().trim();
        precioVenta = parseFloat($('#precio_venta').val());
        esNuevo = true;

        if (!nombre || isNaN(precioVenta) || precioVenta <= 0) {
            alert('Ingrese el nombre y el precio de venta para el producto nuevo');
            return;
        }
    } else {
        idProducto = parseInt(val);
        nombre = select.options[select.selectedIndex].getAttribute('data-nombre');
    }

    const cantidad = parseInt($('#cantidad').val());
    const costoUnitario = parseFloat($('#costo_unitario').val());

    if (isNaN(cantidad) || cantidad <= 0 || isNaN(costoUnitario) || costoUnitario <= 0) {
        alert('Ingrese una cantidad y costo unitario válidos');
        return;
    }

    // Verificar si el producto ya existe en la lista previa
    const index = items.findIndex(i => !i.es_nuevo && i.id_producto === idProducto && idProducto !== 0);
    
    if (index !== -1) {
        items[index].cantidad += cantidad;
        items[index].subtotal = items[index].cantidad * items[index].costo_unitario;
    } else {
        items.push({
            id_producto: idProducto,
            nombre: nombre,
            es_nuevo: esNuevo,
            precio_venta: precioVenta,
            cantidad: cantidad,
            costo_unitario: costoUnitario,
            subtotal: cantidad * costoUnitario
        });
    }

    // Limpiar formulario interno
    select.value = '';
    $('#nuevo_nombre').val('');
    $('#precio_venta').val('0.00');
    $('#costo_unitario').val('0.00');
    $('#cantidad').val('1');
    evaluarProductoNuevo(select);

    renderTabla();
}

function eliminarItem(index) {
    items.splice(index, 1);
    renderTabla();
}

function renderTabla() {
    let html = '';
    let total = 0;

    if (items.length === 0) {
        html = '<tr><td colspan="6" class="text-center text-muted py-4">Sin productos agregados</td></tr>';
    } else {
        items.forEach((item, idx) => {
            total += item.subtotal;
            const badge = item.es_nuevo 
                ? '<span class="badge bg-success">Nuevo</span>' 
                : '<span class="badge bg-secondary">Existente</span>';

            html += `
                <tr>
                    <td>${badge}</td>
                    <td><strong>${item.nombre}</strong></td>
                    <td class="text-center"><span class="badge bg-primary fs-6">+${item.cantidad}</span></td>
                    <td class="text-end">$${item.costo_unitario.toFixed(2)}</td>
                    <td class="text-end fw-bold">$${item.subtotal.toFixed(2)}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm" onclick="eliminarItem(${idx})"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>`;
        });
    }

    $('#tbl-items').html(html);
    $('#total_compra').text('$' + total.toFixed(2));
}

function guardarCompra() {
    const idProveedor = $('#id_proveedor').val();
    if (!idProveedor) {
        alert('Seleccione un proveedor');
        return;
    }
    if (items.length === 0) {
        alert('Agregue al menos un producto a la lista');
        return;
    }

    const total = items.reduce((sum, i) => sum + i.subtotal, 0);

    fetch('<?= base_url("compras/guardar") ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            id_proveedor: idProveedor,
            total: total,
            items: items
        })
    })
    .then(async res => {
        const text = await res.text();
        try {
            return JSON.parse(text);
        } catch (e) {
            throw new Error("Error del servidor: " + text);
        }
    })
    .then(data => {
        if (data.status === 'success') {
            alert(data.msg);
            window.location.href = '<?= base_url("compras") ?>';
        } else {
            alert('Error: ' + (data.msg || 'Ocurrió un problema desconocido'));
        }
    })
    .catch(err => {
        console.error(err);
        alert(err.message);
    });
}
</script>
<?= $this->endSection() ?>