<?= $this->extend('layouts/main') ?>

<?= $this->section('page_title') ?>
Gestión de Proveedores
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Estilos DataTables Bootstrap 5 (Por si no están en tu layout global) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<div class="row">
    <div class="col-12">

        <!-- Botón Superior Nuevo Proveedor -->
        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-primary px-3 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalProveedor" onclick="nuevoProveedor()">
                <i class="fas fa-plus-circle me-1"></i> Nuevo Proveedor
            </button>
        </div>

        <!-- Tarjeta de Contenido con Estilos idénticos a tu captura -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table id="tablaProveedores" class="table table-hover align-middle w-100">
                        <thead>
                            <tr>
                                <th class="text-secondary fw-semibold" style="width: 80px;">ID</th>
                                <th class="text-secondary fw-semibold">Identificación (Cédula/RUC)</th>
                                <th class="text-secondary fw-semibold">Nombre / Razón Social</th>
                                <th class="text-secondary fw-semibold">Teléfono</th>
                                <th class="text-secondary fw-semibold text-center" style="width: 100px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($proveedores)): ?>
                                <?php foreach ($proveedores as $p): ?>
                                    <tr>
                                        <td><?= esc($p['id_proveedor']) ?></td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-secondary-emphasis px-2 py-1 font-monospace border">
                                                <?= esc($p['identificacion']) ?>
                                            </span>
                                        </td>
                                        <td class="fw-medium text-dark"><?= esc($p['nombre']) ?></td>
                                        <td><?= esc($p['telefono'] ?? 'N/A') ?></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-warning btn-sm border-1 me-1 shadow-sm" onclick='editarProveedor(<?= json_encode($p) ?>)' title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="<?= base_url('proveedores/eliminar/' . $p['id_proveedor']) ?>" class="btn btn-outline-danger btn-sm border-1 shadow-sm btn-eliminar" title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
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
</div>

<!-- Modal Crear / Editar -->
<div class="modal fade" id="modalProveedor" tabindex="-1" aria-labelledby="modalProveedorLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="<?= base_url('proveedores/guardar') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="id_proveedor" id="id_proveedor">
                
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalProveedorLabel">Nuevo Proveedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label for="identificacion" class="form-label text-secondary fw-semibold">Identificación (Cédula/RUC) *</label>
                        <input type="text" name="identificacion" id="identificacion" class="form-control rounded-3" required maxlength="20">
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label text-secondary fw-semibold">Nombre / Razón Social *</label>
                        <input type="text" name="nombre" id="nombre" class="form-control rounded-3" required maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label text-secondary fw-semibold">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" class="form-control rounded-3" maxlength="20">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts requeridos para DataTables -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Configuración idéntica a tu diseño
        $('#tablaProveedores').DataTable({
            language: {
                search: "Buscar:",
                lengthMenu: "Mostrar _MENU_ registros",
                info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                zeroRecords: "Ningún dato disponible en esta tabla",
                paginate: {
                    first: "Primero",
                    previous: "Anterior",
                    next: "Siguiente",
                    last: "Último"
                }
            },
            dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center'l><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 d-flex justify-content-end'p>>",
            pageLength: 10,
            responsive: true
        });

        // Evento eliminar
        $(document).on('click', '.btn-eliminar', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción eliminará al proveedor.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            } else if (confirm('¿Estás seguro de eliminar este registro?')) {
                window.location.href = url;
            }
        });

        // Alerta de éxito
        <?php if (session()->getFlashdata('success')): ?>
            if (typeof Swal !== 'undefined') {
                Swal.fire('¡Éxito!', '<?= session()->getFlashdata('success') ?>', 'success');
            }
        <?php endif; ?>
    });

    function nuevoProveedor() {
        $('#id_proveedor').val('');
        $('#identificacion').val('');
        $('#nombre').val('');
        $('#telefono').val('');
        $('#modalProveedorLabel').text('Nuevo Proveedor');
    }

    function editarProveedor(proveedor) {
        $('#id_proveedor').val(proveedor.id_proveedor);
        $('#identificacion').val(proveedor.identificacion);
        $('#nombre').val(proveedor.nombre);
        $('#telefono').val(proveedor.telefono || '');
        $('#modalProveedorLabel').text('Editar Proveedor');
        $('#modalProveedor').modal('show');
    }
</script>
<?= $this->endSection() ?>