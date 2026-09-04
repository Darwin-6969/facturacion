<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Administración de Clientes
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- DataTables CSS Bootstrap 5 -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-sm-6">
            <h3 class="mb-0">Gestión de Clientes</h3>
        </div>
        <div class="col-sm-6 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
                <i class="bi bi-person-plus me-1"></i> Nuevo Cliente
            </button>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="tablaClientes">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">ID</th>
                            <th>Identificación (Cédula/RUC)</th>
                            <th>Nombre Completo</th>
                            <th>Teléfono</th>
                            <th>Correo Electrónico</th>
                            <th class="text-end" style="width: 12%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($clientes)): ?>
                            <?php foreach ($clientes as $cli): ?>
                                <tr>
                                    <td><?= $cli['id_cliente'] ?></td>
                                    <td><span class="badge bg-secondary"><?= esc($cli['identificacion']) ?></span></td>
                                    <td><?= esc($cli['nombre']) ?></td>
                                    <td><?= esc($cli['telefono'] ?? 'N/A') ?></td>
                                    <td><?= esc($cli['correo'] ?? 'N/A') ?></td>
                                    <td class="text-end">
                                        <!-- Botón Editar -->
                                        <button type="button" class="btn btn-sm btn-outline-warning btn-editar" 
                                                data-id="<?= $cli['id_cliente'] ?>" 
                                                data-identificacion="<?= esc($cli['identificacion']) ?>"
                                                data-nombre="<?= esc($cli['nombre']) ?>"
                                                data-telefono="<?= esc($cli['telefono']) ?>"
                                                data-correo="<?= esc($cli['correo']) ?>"
                                                data-bs-toggle="modal" data-bs-target="#modalEditar">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <!-- Botón Eliminar con SweetAlert2 -->
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar" 
                                                data-url="<?= base_url('clientes/eliminar/' . $cli['id_cliente']) ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
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

<!-- Modal Crear -->
<div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('clientes/guardar') ?>" method="POST" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Nuevo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="identificacion" class="form-label fw-semibold">Identificación / Cédula <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="identificacion" name="identificacion" required minlength="10" maxlength="13" placeholder="Ej. 1003456789">
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label fw-semibold">Nombre Completo <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required minlength="3" maxlength="100" placeholder="Ej. Juan Pérez">
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label fw-semibold">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" maxlength="20" placeholder="Ej. 0991234567">
                </div>
                <div class="mb-3">
                    <label for="correo" class="form-label fw-semibold">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" maxlength="100" placeholder="Ej. cliente@correo.com">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Cliente</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formEditar" method="POST" class="modal-content">
            <?= csrf_field() ?>
            <input type="hidden" name="id_cliente" id="idEditar">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Editar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="identificacionEditar" class="form-label fw-semibold">Identificación / Cédula <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="identificacionEditar" name="identificacion" required minlength="10" maxlength="13">
                </div>
                <div class="mb-3">
                    <label for="nombreEditar" class="form-label fw-semibold">Nombre Completo <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nombreEditar" name="nombre" required minlength="3" maxlength="100">
                </div>
                <div class="mb-3">
                    <label for="telefonoEditar" class="form-label fw-semibold">Teléfono</label>
                    <input type="text" class="form-control" id="telefonoEditar" name="telefono" maxlength="20">
                </div>
                <div class="mb-3">
                    <label for="correoEditar" class="form-label fw-semibold">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correoEditar" name="correo" maxlength="100">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Actualizar Cambios</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- jQuery & DataTables -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Inicialización de DataTable
        $('#tablaClientes').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            columnDefs: [
                { orderable: false, targets: 5 }
            ],
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]]
        });

        // 2. Configuración Toast
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        // 3. Alertas Flash
        <?php if (session()->has('success')): ?>
            Toast.fire({ icon: 'success', title: '<?= addslashes(session('success')) ?>' });
        <?php endif; ?>

        <?php if (session()->has('error')): ?>
            Toast.fire({ icon: 'error', title: '<?= addslashes(session('error')) ?>' });
        <?php endif; ?>

        <?php if (session()->has('errors')): ?>
            <?php foreach (session('errors') as $error): ?>
                Toast.fire({ icon: 'error', title: '<?= addslashes($error) ?>' });
            <?php endforeach; ?>
        <?php endif; ?>

        // 4. Confirmación de Eliminación
        $(document).on('click', '.btn-eliminar', function(e) {
            e.preventDefault();
            let urlEliminar = $(this).data('url');
            Swal.fire({
                title: '¿Está seguro?',
                text: "¡Esta acción no se puede revertir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-trash"></i> Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = urlEliminar;
                }
            });
        });

        // 5. Cargar datos en Modal Editar
        const modalEditar = document.getElementById('modalEditar');
        modalEditar.addEventListener('show.bs.modal', function(event) {
            let button = event.relatedTarget;
            
            let id = button.getAttribute('data-id');
            let identificacion = button.getAttribute('data-identificacion');
            let nombre = button.getAttribute('data-nombre');
            let telefono = button.getAttribute('data-telefono');
            let correo = button.getAttribute('data-correo');

            modalEditar.querySelector('#idEditar').value = id;
            modalEditar.querySelector('#identificacionEditar').value = identificacion;
            modalEditar.querySelector('#nombreEditar').value = nombre;
            modalEditar.querySelector('#telefonoEditar').value = telefono;
            modalEditar.querySelector('#correoEditar').value = correo;

            modalEditar.querySelector('#formEditar').action = '<?= base_url('clientes/guardar') ?>';
        });
    });
</script>
<?= $this->endSection() ?>