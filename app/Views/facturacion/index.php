<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Historial de Facturas
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-sm-6">
            <h3 class="mb-0"><i class="bi bi-clock-history me-2"></i>Historial de Facturación</h3>
        </div>
        <div class="col-sm-6 text-end">
            <a href="<?= base_url('facturas/nueva') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Nueva Factura
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="tablaFacturas">
                    <thead class="table-light">
                        <tr>
                            <th>ID Venta</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Identificación</th>
                            <th>Atendido por</th>
                            <th class="text-end">Total ($)</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($facturas)): ?>
                            <?php foreach ($facturas as $f): ?>
                                <tr>
                                    <td><strong>#<?= $f['id_venta'] ?></strong></td>
                                    <td><?= date('d/m/Y H:i', strtotime($f['fecha'])) ?></td>
                                    <td><?= esc($f['cliente_nombre']) ?></td>
                                    <td><?= esc($f['cliente_identificacion']) ?></td>
                                    <td><?= esc($f['usuario_nombre']) ?></td>
                                    <td class="text-end fw-bold text-success">$<?= number_format($f['total'], 2) ?></td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-info btn-ver-detalle" data-id="<?= $f['id_venta'] ?>" title="Ver Detalle">
                                                <i class="bi bi-eye-fill me-1"></i> Ver Detalle
                                            </button>
                                            <a href="<?= base_url('facturas/pdf/' . $f['id_venta']) ?>" class="btn btn-sm btn-outline-danger" target="_blank" title="Imprimir PDF">
                                                <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Detalle de Factura -->
<div class="modal fade" id="modalVerDetalle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="tituloModalFactura"><i class="bi bi-receipt me-2"></i>Detalle de Factura</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3 border-bottom pb-2">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Cliente:</strong> <span id="detCliente"></span></p>
                        <p class="mb-1"><strong>Identificación:</strong> <span id="detIdentificacion"></span></p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-1"><strong>Fecha:</strong> <span id="detFecha"></span></p>
                        <p class="mb-1"><strong>Atendido por:</strong> <span id="detUsuario"></span></p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Precio U. ($)</th>
                                <th class="text-end">Subtotal ($)</th>
                            </tr>
                        </thead>
                        <tbody id="detTablaProductos"></tbody>
                    </table>
                </div>

                <div class="row justify-content-end">
                    <div class="col-md-5">
                        <div class="p-2 border rounded bg-light text-end">
                            <p class="fs-5 fw-bold mb-0 text-success">Total Pagado: <span id="detTotal"></span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="btnImprimirModal" class="btn btn-danger" target="_blank">
                    <i class="bi bi-printer-fill me-1"></i> Imprimir PDF
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#tablaFacturas').DataTable({
        language: { url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" },
        order: [[0, 'desc']]
    });

    $(document).on('click', '.btn-ver-detalle', function() {
        let idVenta = $(this).data('id');
        
        $.get('<?= base_url('facturacion/verDetalle/') ?>' + idVenta, function(res) {
            if (res.status === 'success') {
                $('#tituloModalFactura').html(`<i class="bi bi-receipt me-2"></i>Factura #${res.venta.id_venta}`);
                $('#detCliente').text(res.venta.cliente_nombre);
                $('#detIdentificacion').text(res.venta.identificacion);
                $('#detFecha').text(res.venta.fecha);
                $('#detUsuario').text(res.venta.usuario_nombre);
                $('#detTotal').text('$' + parseFloat(res.venta.total).toFixed(2));
                
                // Actualiza el enlace de impresión dentro del Modal
                $('#btnImprimirModal').attr('href', '<?= base_url('facturas/pdf/') ?>' + res.venta.id_venta);

                let rows = '';
                res.detalles.forEach(d => {
                    rows += `<tr>
                        <td>${d.producto_nombre}</td>
                        <td class="text-center">${d.cantidad}</td>
                        <td class="text-end">$${parseFloat(d.precio_unitario).toFixed(2)}</td>
                        <td class="text-end">$${parseFloat(d.subtotal).toFixed(2)}</td>
                    </tr>`;
                });

                $('#detTablaProductos').html(rows);
                let modal = new bootstrap.Modal(document.getElementById('modalVerDetalle'));
                modal.show();
            }
        });
    });
});
</script>
<?= $this->endSection() ?>