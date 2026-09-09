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
            <!-- 1. BÚSQUEDA DE PROVEEDOR -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white fw-bold d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-truck me-1"></i> 1. Proveedor</span>
                    <button type="button" class="btn btn-sm btn-light text-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor">
                        <i class="fas fa-plus"></i> Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <label class="form-label fw-bold">Buscar por Cédula/RUC o Nombre</label>
                    <div class="position-relative">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="buscar_proveedor" class="form-control" placeholder="Escriba Cédula, RUC o Nombre..." autocomplete="off">
                        </div>
                        <div id="lista_sugerencias_prov" class="list-group position-absolute w-100 shadow-lg d-none" style="z-index: 1050; max-height: 200px; overflow-y: auto;"></div>
                    </div>

                    <input type="hidden" id="id_proveedor" value="">
                    <div id="box_proveedor_seleccionado" class="alert alert-success mt-2 mb-0 p-2 d-none">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="fw-bold d-block text-uppercase" id="lbl_prov_nombre"></small>
                                <small class="text-muted" id="lbl_prov_cedula"></small>
                            </div>
                            <button type="button" class="btn-close btn-sm" onclick="deseleccionarProveedor()"></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. BÚSQUEDA DE PRODUCTO -->
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-box me-1"></i> 2. Detalle del Producto</span>
                    <button type="button" id="btn_toggle_nuevo_prod" class="btn btn-sm btn-success fw-bold" onclick="toggleCrearNuevoProducto(true)">
                        <i class="fas fa-plus"></i> Crear Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <!-- Búsqueda dinámica de producto existente -->
                    <div id="box_buscar_producto">
                        <label class="form-label fw-bold">Buscar Producto Existente</label>
                        <div class="position-relative">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" id="buscar_producto" class="form-control" placeholder="Escriba el nombre del producto..." autocomplete="off">
                            </div>
                            <div id="lista_sugerencias_prod" class="list-group position-absolute w-100 shadow-lg d-none" style="z-index: 1050; max-height: 200px; overflow-y: auto;"></div>
                        </div>

                        <input type="hidden" id="id_producto_sel" value="">
                        <div id="box_producto_seleccionado" class="alert alert-info mt-2 mb-0 p-2 d-none">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="fw-bold d-block" id="lbl_prod_nombre"></small>
                                </div>
                                <button type="button" class="btn-close btn-sm" onclick="deseleccionarProducto()"></button>
                            </div>
                        </div>
                    </div>

                    <!-- Input para registrar un nuevo producto -->
                    <div id="box_nuevo_producto" class="d-none">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label text-success fw-bold mb-0">Registrar Producto Nuevo</label>
                            <button type="button" class="btn btn-link btn-sm text-secondary p-0" onclick="toggleCrearNuevoProducto(false)">Volver a Buscar</button>
                        </div>
                        <input type="text" id="nuevo_nombre" class="form-control border-success mb-3" placeholder="Ej: Teclado Mecánico RGB">
                    </div>

                    <div class="row mt-3">
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
                        <small class="text-muted">Se registrará en el catálogo para su venta.</small>
                    </div>

                    <button type="button" onclick="agregarItem()" class="btn btn-success w-100 fw-bold">
                        <i class="fas fa-plus me-1"></i> Añadir a la Compra
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla Resumen de Compra -->
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
const proveedores = <?= json_encode($proveedores ?? []) ?>;
const productos = <?= json_encode($productos ?? []) ?>;
let items = [];
let esNuevoProducto = false;

$(document).ready(function() {
    // --- BÚSQUEDA DE PROVEEDORES ---
    $('#buscar_proveedor').on('input', function() {
        const query = $(this).val().toLowerCase().trim();
        const $lista = $('#lista_sugerencias_prov');

        if (query.length < 1) {
            $lista.addClass('d-none').empty();
            return;
        }

        const resultados = proveedores.filter(p => {
            const nombre = (p.nombre || '').toLowerCase();
            const doc = (p.ruc || p.cedula || p.identificacion || '').toLowerCase();
            return nombre.includes(query) || doc.includes(query);
        });

        if (resultados.length === 0) {
            $lista.html('<div class="list-group-item text-muted text-center py-2">No se encontraron resultados</div>').removeClass('d-none');
            return;
        }

        let html = '';
        resultados.forEach(p => {
            const id = p.id_proveedor;
            const nombre = encodeURIComponent(p.nombre || '');
            const doc = encodeURIComponent(p.ruc || p.cedula || p.identificacion || 'Sin Documento');
            html += `
                <button type="button" class="list-group-item list-group-item-action text-start" onclick="seleccionarProveedor(${id}, '${nombre}', '${doc}')">
                    <div class="fw-bold">${p.nombre}</div>
                    <small class="text-muted">Doc: ${p.ruc || p.cedula || p.identificacion || 'Sin Documento'}</small>
                </button>`;
        });

        $lista.html(html).removeClass('d-none');
    });

    // --- BÚSQUEDA DE PRODUCTOS ---
    $('#buscar_producto').on('input', function() {
        const query = $(this).val().toLowerCase().trim();
        const $lista = $('#lista_sugerencias_prod');

        if (query.length < 1) {
            $lista.addClass('d-none').empty();
            return;
        }

        const resultados = productos.filter(p => {
            const nombre = (p.nombre || '').toLowerCase();
            return nombre.includes(query);
        });

        if (resultados.length === 0) {
            const queryEnc = encodeURIComponent(query);
            $lista.html(`
                <div class="list-group-item text-muted text-center py-2">
                    No existe el producto.<br>
                    <button type="button" class="btn btn-sm btn-outline-success mt-1 fw-bold" onclick="toggleCrearNuevoProducto(true, '${queryEnc}')">
                        + Crear "${query}"
                    </button>
                </div>`).removeClass('d-none');
            return;
        }

        let html = '';
        resultados.forEach(p => {
            const nombreEnc = encodeURIComponent(p.nombre || '');
            html += `
                <button type="button" class="list-group-item list-group-item-action text-start" onclick="seleccionarProducto(${p.id_producto}, '${nombreEnc}', ${p.precio_venta || 0})">
                    <div class="fw-bold">${p.nombre}</div>
                    <small class="text-muted">Stock actual: ${p.stock || 0}</small>
                </button>`;
        });

        $lista.html(html).removeClass('d-none');
    });

    // Ocultar sugerencias al hacer clic fuera
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#buscar_proveedor, #lista_sugerencias_prov').length) {
            $('#lista_sugerencias_prov').addClass('d-none');
        }
        if (!$(e.target).closest('#buscar_producto, #lista_sugerencias_prod').length) {
            $('#lista_sugerencias_prod').addClass('d-none');
        }
    });
});

// Funciones Proveedor
function seleccionarProveedor(id, nombreEnc, docEnc) {
    const nombre = decodeURIComponent(nombreEnc);
    const doc = decodeURIComponent(docEnc);

    $('#id_proveedor').val(id);
    $('#lbl_prov_nombre').text(nombre);
    $('#lbl_prov_cedula').text('Doc: ' + doc);
    $('#buscar_proveedor').val('').addClass('d-none');
    $('#lista_sugerencias_prov').addClass('d-none').empty();
    $('#box_proveedor_seleccionado').removeClass('d-none');
}

function deseleccionarProveedor() {
    $('#id_proveedor').val('');
    $('#box_proveedor_seleccionado').addClass('d-none');
    $('#buscar_proveedor').removeClass('d-none').focus();
}

// Funciones Producto
function seleccionarProducto(id, nombreEnc, precioVenta) {
    const nombre = decodeURIComponent(nombreEnc);

    $('#id_producto_sel').val(id);
    $('#lbl_prod_nombre').text(nombre);
    $('#buscar_producto').val('').addClass('d-none');
    $('#lista_sugerencias_prod').addClass('d-none').empty();
    $('#box_producto_seleccionado').removeClass('d-none');
}

function deseleccionarProducto() {
    $('#id_producto_sel').val('');
    $('#box_producto_seleccionado').addClass('d-none');
    $('#buscar_producto').removeClass('d-none').focus();
}

function toggleCrearNuevoProducto(activar, textoInicialEnc = '') {
    esNuevoProducto = activar;
    if (activar) {
        deseleccionarProducto();
        $('#box_buscar_producto').addClass('d-none');
        $('#box_nuevo_producto').removeClass('d-none');
        $('#box_precio_venta').removeClass('d-none');
        $('#btn_toggle_nuevo_prod').addClass('d-none');
        if (textoInicialEnc) $('#nuevo_nombre').val(decodeURIComponent(textoInicialEnc));
        $('#nuevo_nombre').focus();
    } else {
        $('#box_buscar_producto').removeClass('d-none');
        $('#box_nuevo_producto').addClass('d-none');
        $('#box_precio_venta').addClass('d-none');
        $('#btn_toggle_nuevo_prod').removeClass('d-none');
        $('#nuevo_nombre').val('');
    }
}

function agregarItem() {
    let idProducto = 0;
    let nombre = '';
    let precioVenta = 0;

    if (esNuevoProducto) {
        nombre = $('#nuevo_nombre').val().trim();
        precioVenta = parseFloat($('#precio_venta').val());

        if (!nombre || isNaN(precioVenta) || precioVenta <= 0) {
            alert('Ingrese el nombre y el precio de venta para el producto nuevo');
            return;
        }
    } else {
        idProducto = parseInt($('#id_producto_sel').val());
        nombre = $('#lbl_prod_nombre').text().trim();

        if (!idProducto) {
            alert('Busque y seleccione un producto existente o active la opción para crear uno nuevo');
            return;
        }
    }

    const cantidad = parseInt($('#cantidad').val());
    const costoUnitario = parseFloat($('#costo_unitario').val());

    if (isNaN(cantidad) || cantidad <= 0 || isNaN(costoUnitario) || costoUnitario <= 0) {
        alert('Ingrese una cantidad y costo unitario válidos');
        return;
    }

    const index = items.findIndex(i => !i.es_nuevo && i.id_producto === idProducto && idProducto !== 0);
    
    if (index !== -1) {
        items[index].cantidad += cantidad;
        items[index].subtotal = items[index].cantidad * items[index].costo_unitario;
    } else {
        items.push({
            id_producto: idProducto,
            nombre: nombre,
            es_nuevo: esNuevoProducto,
            precio_venta: precioVenta,
            cantidad: cantidad,
            costo_unitario: costoUnitario,
            subtotal: cantidad * costoUnitario
        });
    }

    // Resetear formulario
    deseleccionarProducto();
    toggleCrearNuevoProducto(false);
    $('#costo_unitario').val('0.00');
    $('#precio_venta').val('0.00');
    $('#cantidad').val('1');

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
        alert('Por favor busque y seleccione un proveedor válido');
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