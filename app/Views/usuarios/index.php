<?= $this->extend('layouts/main') ?>

<?= $this->section('page_title') ?>
Gestión de Usuarios
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<div class="row">
    <div class="col-12">

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-primary px-3 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalUsuario" onclick="nuevoUsuario()">
                <i class="fas fa-user-plus me-1"></i> Nuevo Usuario
            </button>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table id="tablaUsuarios" class="table table-hover align-middle w-100">
                        <thead>
                            <tr>
                                <th class="text-secondary fw-semibold" style="width: 60px;">ID</th>
                                <th class="text-secondary fw-semibold">Nombre Completo</th>
                                <th class="text-secondary fw-semibold">Correo Electrónico</th>
                                <th class="text-secondary fw-semibold">Rol</th>
                                <th class="text-secondary fw-semibold">Estado</th>
                                <th class="text-secondary fw-semibold text-center" style="width: 100px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($usuarios)): ?>
                                <?php foreach ($usuarios as $u): ?>
                                    <tr>
                                        <td><?= esc($u['id_usuario']) ?></td>
                                        <td class="fw-medium text-dark"><?= esc($u['nombre']) ?></td>
                                        <td><?= esc($u['correo']) ?></td>
                                        <td>
                                            <span class="badge <?= $u['rol'] === 'administrador' ? 'bg-primary' : 'bg-info' ?>">
                                                <?= ucfirst(esc($u['rol'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?= $u['estado'] ? 'bg-success' : 'bg-danger' ?>">
                                                <?= $u['estado'] ? 'Activo' : 'Inactivo' ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-warning btn-sm border-1 me-1 shadow-sm" onclick='editarUsuario(<?= json_encode($u) ?>)' title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="<?= base_url('usuarios/eliminar/' . $u['id_usuario']) ?>" class="btn btn-outline-danger btn-sm border-1 shadow-sm btn-eliminar" title="Eliminar">
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
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="<?= base_url('usuarios/guardar') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="id_usuario" id="id_usuario">
                
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalUsuarioLabel">Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label for="nombre" class="form-label text-secondary fw-semibold">Nombre Completo *</label>
                        <input type="text" name="nombre" id="nombre" class="form-control rounded-3" required maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label text-secondary fw-semibold">Correo Electrónico *</label>
                        <input type="email" name="correo" id="correo" class="form-control rounded-3" required maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label for="rol" class="form-label text-secondary fw-semibold">Rol *</label>
                        <select name="rol" id="rol" class="form-select rounded-3" required>
                            <option value="administrador">Administrador</option>
                            <option value="encargado">Encargado</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="clave" class="form-label text-secondary fw-semibold" id="labelClave">Contraseña *</label>
                        <input type="password" name="clave" id="clave" class="form-control rounded-3" maxlength="255">
                        <small class="text-muted d-none" id="helpClave">Dejar en blanco si no se desea cambiar la contraseña.</small>
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

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tablaUsuarios').DataTable({
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

        $(document).on('click', '.btn-eliminar', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción eliminará al usuario seleccionado.",
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

        <?php if (session()->getFlashdata('success')): ?>
            if (typeof Swal !== 'undefined') {
                Swal.fire('¡Éxito!', '<?= session()->getFlashdata('success') ?>', 'success');
            }
        <?php endif; ?>
    });

    function nuevoUsuario() {
        $('#id_usuario').val('');
        $('#nombre').val('');
        $('#correo').val('');
        $('#rol').val('administrador');
        $('#clave').val('');
        $('#clave').prop('required', true);
        $('#labelClave').text('Contraseña *');
        $('#helpClave').addClass('d-none');
        $('#modalUsuarioLabel').text('Nuevo Usuario');
    }

    function editarUsuario(u) {
        $('#id_usuario').val(u.id_usuario);
        $('#nombre').val(u.nombre);
        $('#correo').val(u.correo);
        $('#rol').val(u.rol);
        $('#clave').val('');
        $('#clave').prop('required', false);
        $('#labelClave').text('Nueva Contraseña (Opcional)');
        $('#helpClave').removeClass('d-none');
        $('#modalUsuarioLabel').text('Editar Usuario');
        $('#modalUsuario').modal('show');
    }
</script>
<?= $this->endSection() ?>