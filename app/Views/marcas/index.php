<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Administración de Marcas
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- DataTables CSS Bootstrap 5 -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-sm-6">
            <h3 class="mb-0">Gestión de Marcas</h3>
        </div>
        <div class="col-sm-6 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
                <i class="bi bi-plus-circle me-1"></i> Nueva Marca
            </button>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="tablaMarcas">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 10%;">ID</th>
                            <th>Nombre de Marca</th>
                            <th class="text-end" style="width: 15%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($marcas)): ?>
                            <?php foreach ($marcas as $m): ?>
                                <tr>
                                    <td><?= $m['id_marca'] ?></td>
                                    <td><?= esc($m['nombre']) ?></td>
                                    <td class="text-end">
                                        <!-- Botón Editar -->
                                        <button type="button" class="btn btn-sm btn-outline-warning btn-editar" 
                                                data-id="<?= $m['id_marca'] ?>" 
                                                data-nombre="<?= esc($m['nombre']) ?>"
                                                data-bs-toggle="modal" data-bs-target="#modalEditar">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <!-- Botón Eliminar con SweetAlert2 -->
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar" 
                                                data-url="<?= base_url('marcas/eliminar/' . $m['id_marca']) ?>">
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
        <form action="<?= base_url('marcas/guardar') ?>" method="POST" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Nueva Marca</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="nombre" class="form-label fw-semibold">Nombre de la Marca <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required minlength="2" maxlength="50" placeholder="Ej. Samsung">
                    <div class="invalid-feedback">Por favor ingrese un nombre válido (2-50 caracteres).</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Marca</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formEditar" method="POST" class="modal-content">
            <?= csrf_field() ?>
            <input type="hidden" name="id_marca" id="idEditar">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Editar Marca</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="nombreEditar" class="form-label fw-semibold">Nombre de la Marca <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nombreEditar" name="nombre" required minlength="2" maxlength="50">
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
        // 1. Inicializar DataTable
        $('#tablaMarcas').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            columnDefs: [
                { orderable: false, targets: 2 }
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

        // 3. Notificaciones Flash
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

        // 4. Confirmación Eliminar
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

        // 5. Cargar datos al modal editar
        const modalEditar = document.getElementById('modalEditar');
        modalEditar.addEventListener('show.bs.modal', function(event) {
            let button = event.relatedTarget;
            let id = button.getAttribute('data-id');
            let nombre = button.getAttribute('data-nombre');

            let inputId = modalEditar.querySelector('#idEditar');
            let inputNombre = modalEditar.querySelector('#nombreEditar');
            let form = modalEditar.querySelector('#formEditar');

            inputId.value = id;
            inputNombre.value = nombre;
            form.action = '<?= base_url('marcas/guardar') ?>';
        });
    });
</script>
<?= $this->endSection() ?>