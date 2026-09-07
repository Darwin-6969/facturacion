<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Nueva Factura
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .results-dropdown {
        max-height: 200px;
        overflow-y: auto;
        z-index: 1050;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-sm-6">
            <h3 class="mb-0"><i class="bi bi-cart-plus me-2"></i>Nueva Factura de Venta</h3>
        </div>
        <div class="col-sm-6 text-end">
            <a href="<?= base_url('facturas') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver al Historial
            </a>
        </div>
    </div>

    <div class="row">
        <!-- SECCIÓN MAESTRO: BUSCADOR DE CLIENTE -->
        <div class="col-md-12 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-semibold">
                    <i class="bi bi-person-fill me-1"></i> Información del Cliente
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-5 position-relative">
                            <label class="form-label fw-bold">Buscar Cliente (Cédula o Nombre)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" id="buscar_cliente" class="form-control" placeholder="Escriba para buscar...">
                            </div>
                            <ul class="list-group position-absolute w-100 results-dropdown shadow mt-1" id="res_cliente" style="display:none;"></ul>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Cliente Seleccionado</label>
                            <input type="hidden" id="id_cliente" name="id_cliente">
                            <input type="text" id="nombre_cliente" class="form-control" readonly placeholder="Ningún cliente seleccionado">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Identificación / Cédula</label>
                            <input type="text" id="identificacion_cliente" class="form-control" readonly placeholder="N/A">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN DETALLE: BÚSQUEDA Y AGREGADO DE PRODUCTOS -->
        <div class="col-md-12 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white fw-semibold">
                    <i class="bi bi-box-seam me-1"></i> Búsqueda de Productos
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6 position-relative">
                            <label class="form-label fw-bold">Buscar Producto (Código o Nombre)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" id="buscar_producto" class="form-control" placeholder="Buscar por código de barras o descripción...">
                            </div>
                            <ul class="list-group position-absolute w-100 results-dropdown shadow mt-1" id="res_producto" style="display:none;"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABLA DETALLE DE VENTAS -->
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="tablaDetalle">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th style="width: 120px;" class="text-center">Stock</th>
                                    <th style="width: 140px;" class="text-end">Precio U. ($)</th>
                                    <th style="width: 130px;" class="text-center">Cantidad</th>
                                    <th style="width: 150px;" class="text-end">Subtotal ($)</th>
                                    <th style="width: 80px;" class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="filaVacia">
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-cart-x fs-3 d-block mb-1"></i> No se han añadido productos a la factura.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- RESUMEN DE TOTALES E IMPUESTOS -->
                    <div class="row justify-content-end mt-4">
                        <div class="col-md-4">
                            <ul class="list-group shadow-sm">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Subtotal (sin impuestos):
                                    <span class="fw-bold fs-6" id="lblSubtotal">$0.00</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    IVA (15%):
                                    <span class="fw-bold fs-6" id="lblIva">$0.00</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-light fs-5 fw-bold">
                                    Total Factura:
                                    <span class="text-success" id="lblTotal">$0.00</span>
                                </li>
                            </ul>
                            <button class="btn btn-success w-100 mt-3 btn-lg shadow" id="btnGuardarVenta">
                                <i class="bi bi-check-circle me-1"></i> Completar Venta
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    let productosDetalle = [];

    // --- Búsqueda y Selección de Cliente ---
    $('#buscar_cliente').on('keyup', function() {
        let q = $(this).val().trim();
        if (q.length >= 2) {
            $.get('<?= base_url('facturacion/buscarCliente') ?>', { q: q }, function(data) {
                let html = '';
                if (data.length > 0) {
                    data.forEach(c => {
                        html += `<li class="list-group-item list-group-item-action item-cliente" 
                                    style="cursor:pointer;"
                                    data-id="${c.id_cliente}" 
                                    data-nombre="${c.nombre}" 
                                    data-identificacion="${c.identificacion}">
                                    <strong>${c.identificacion}</strong> - ${c.nombre}
                                </li>`;
                    });
                } else {
                    html = `<li class="list-group-item text-muted">No se encontraron clientes</li>`;
                }
                $('#res_cliente').html(html).show();
            });
        } else {
            $('#res_cliente').hide();
        }
    });

    $(document).on('click', '.item-cliente', function() {
        $('#id_cliente').val($(this).data('id'));
        $('#nombre_cliente').val($(this).data('nombre'));
        $('#identificacion_cliente').val($(this).data('identificacion'));
        $('#res_cliente').hide();
        $('#buscar_cliente').val('');
    });

    // --- Búsqueda y Selección de Producto ---
    $('#buscar_producto').on('keyup', function() {
        let q = $(this).val().trim();
        if (q.length >= 2) {
            $.get('<?= base_url('facturacion/buscarProducto') ?>', { q: q }, function(data) {
                let html = '';
                if (data.length > 0) {
                    data.forEach(p => {
                        html += `<li class="list-group-item list-group-item-action item-producto" 
                                    style="cursor:pointer;"
                                    data-id="${p.id_producto}" 
                                    data-nombre="${p.nombre}" 
                                    data-precio="${p.precio_venta}" 
                                    data-stock="${p.stock}">
                                    <strong>${p.nombre}</strong> <span class="badge bg-secondary ms-1">Cod: ${p.codigo_barras || 'N/A'}</span>
                                    <div class="small text-muted">Precio: $${parseFloat(p.precio_venta).toFixed(2)} | Stock: <b class="text-primary">${p.stock}</b></div>
                                </li>`;
                    });
                } else {
                    html = `<li class="list-group-item text-muted">No hay productos o no disponen de stock</li>`;
                }
                $('#res_producto').html(html).show();
            });
        } else {
            $('#res_producto').hide();
        }
    });

    $(document).on('click', '.item-producto', function() {
        let id = $(this).data('id');
        let nombre = $(this).data('nombre');
        let precio = parseFloat($(this).data('precio'));
        let stock = parseInt($(this).data('stock'));

        let existe = productosDetalle.find(p => p.id_producto === id);
        if (existe) {
            if (existe.cantidad + 1 > stock) {
                Swal.fire('Stock Limitado', `No puede agregar más unidades. El stock máximo disponible es ${stock}`, 'warning');
                return;
            }
            existe.cantidad++;
        } else {
            productosDetalle.push({
                id_producto: id,
                nombre: nombre,
                precio: precio,
                stock: stock,
                cantidad: 1
            });
        }

        $('#res_producto').hide();
        $('#buscar_producto').val('');
        renderTabla();
    });

    // --- Cambiar Cantidad ---
    $(document).on('change keyup', '.cant-prod', function() {
        let index = $(this).data('index');
        let cant = parseInt($(this).val()) || 1;

        if (cant < 1) cant = 1;

        if (cant > productosDetalle[index].stock) {
            Swal.fire('Stock Excedido', `El stock máximo disponible para este producto es ${productosDetalle[index].stock}`, 'warning');
            cant = productosDetalle[index].stock;
            $(this).val(cant);
        }

        productosDetalle[index].cantidad = cant;
        renderTabla();
    });

    // --- Eliminar Item de la Tabla ---
    $(document).on('click', '.btn-eliminar-item', function() {
        let index = $(this).data('index');
        productosDetalle.splice(index, 1);
        renderTabla();
    });

    // --- Renderizar Tabla de Detalle y Calcular Totales ---
    function renderTabla() {
        if (productosDetalle.length === 0) {
            $('#tablaDetalle tbody').html(`
                <tr id="filaVacia">
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="bi bi-cart-x fs-3 d-block mb-1"></i> No se han añadido productos a la factura.
                    </td>
                </tr>
            `);
            $('#lblSubtotal').text('$0.00');
            $('#lblIva').text('$0.00');
            $('#lblTotal').text('$0.00');
            return;
        }

        let html = '';
        let subtotal = 0;

        productosDetalle.forEach((p, i) => {
            let st = p.cantidad * p.precio;
            subtotal += st;
            html += `<tr>
                <td><span class="fw-semibold">${p.nombre}</span></td>
                <td class="text-center"><span class="badge bg-info text-dark">${p.stock}</span></td>
                <td class="text-end">$${p.precio.toFixed(2)}</td>
                <td class="text-center">
                    <input type="number" min="1" max="${p.stock}" class="form-control form-control-sm text-center cant-prod mx-auto" style="width: 80px;" data-index="${i}" value="${p.cantidad}">
                </td>
                <td class="text-end fw-bold">$${st.toFixed(2)}</td>
                <td class="text-center">
                    <button class="btn btn-outline-danger btn-sm btn-eliminar-item" data-index="${i}"><i class="bi bi-trash"></i></button>
                </td>
            </tr>`;
        });

        $('#tablaDetalle tbody').html(html);

        let iva = subtotal * 0.15;
        let total = subtotal + iva;

        $('#lblSubtotal').text('$' + subtotal.toFixed(2));
        $('#lblIva').text('$' + iva.toFixed(2));
        $('#lblTotal').text('$' + total.toFixed(2));
    }

    // --- Enviar Registro de Venta ---
    $('#btnGuardarVenta').click(function() {
        let idCliente = $('#id_cliente').val();
        if (!idCliente) {
            Swal.fire('Atención', 'Por favor, busque y seleccione un cliente.', 'warning');
            return;
        }
        if (productosDetalle.length === 0) {
            Swal.fire('Atención', 'Debe agregar al menos un producto a la factura.', 'warning');
            return;
        }

        Swal.fire({
            title: '¿Confirmar Factura?',
            text: "Se registrará la factura y los productos se descontarán del stock.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, Facturar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('facturacion/guardar') ?>',
                    method: 'POST',
                    data: {
                        <?= csrf_token() ?>: '<?= csrf_hash() ?>',
                        id_cliente: idCliente,
                        productos: productosDetalle
                    },
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire('¡Venta Exitosa!', res.message, 'success').then(() => {
                                window.location.href = '<?= base_url('facturas') ?>';
                            });
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Ocurrió un error inesperado al procesar el servidor.', 'error');
                    }
                });
            }
        });
    });

    // Ocultar desplegables si se hace clic fuera
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#buscar_cliente, #res_cliente').length) $('#res_cliente').hide();
        if (!$(e.target).closest('#buscar_producto, #res_producto').length) $('#res_producto').hide();
    });
});
</script>
<?= $this->endSection() ?>