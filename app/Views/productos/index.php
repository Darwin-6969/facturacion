<?= $this->extend('layouts/main') ?>

<?= $this->section('page_title') ?>
Gestión de Productos
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
            <button type="button" class="btn btn-primary px-3 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalProducto" onclick="nuevoProducto()">
                <i class="fas fa-box-open me-1"></i> Nuevo Producto
            </button>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table id="tablaProductos" class="table table-hover align-middle w-100">
                        <thead>
                            <tr>
                                <th class="text-secondary fw-semibold" style="width: 60px;">ID</th>
                                <th class="text-secondary fw-semibold">Cód. Barras</th>
                                <th class="text-secondary fw-semibold">Nombre</th>
                                <th class="text-secondary fw-semibold">Categoría</th>
                                <th class="text-secondary fw-semibold">Marca</th>
                                <th class="text-secondary fw-semibold">Precio Venta</th>
                                <th class="text-secondary fw-semibold">Stock</th>
                                <th class="text-secondary fw-semibold text-center" style="width: 100px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($productos)): ?>
                                <?php foreach ($productos as $p): ?>
                                    <tr>
                                        <td><?= esc($p['id_producto']) ?></td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-secondary-emphasis px-2 py-1 font-monospace border">
                                                <?= esc($p['codigo_barras'] ?? 'N/A') ?>
                                            </span>
                                        </td>
                                        <td class="fw-medium text-dark"><?= esc($p['nombre']) ?></td>
                                        <td><span class="badge bg-light text-dark border"><?= esc($p['categoria']) ?></span></td>
                                        <td><span class="badge bg-light text-dark border"><?= esc($p['marca']) ?></span></td>
                                        <td class="fw-bold text-success">$<?= number_format($p['precio_venta'], 2) ?></td>
                                        <td>
                                            <span class="badge <?= $p['stock'] > 5 ? 'bg-success' : 'bg-danger' ?>">
                                                <?= esc($p['stock']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-warning btn-sm border-1 me-1 shadow-sm" onclick='editarProducto(<?= json_encode($p) ?>)' title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="<?= base_url('productos/eliminar/' . $p['id_producto']) ?>" class="btn btn-outline-danger btn-sm border-1 shadow-sm btn-eliminar" title="Eliminar">
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
<div class="modal fade" id="modalProducto" tabindex="-1" aria-labelledby="modalProductoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="<?= base_url('productos/guardar') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="id_producto" id="id_producto">
                
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalProductoLabel">Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label for="codigo_barras" class="form-label text-secondary fw-semibold">Código de Barras</label>
                        <input type="text" name="codigo_barras" id="codigo_barras" class="form-control rounded-3" maxlength="50">
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label text-secondary fw-semibold">Nombre del Producto *</label>
                        <input type="text" name="nombre" id="nombre" class="form-control rounded-3" required maxlength="100">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_categoria" class="form-label text-secondary fw-semibold">Categoría *</label>
                            <select name="id_categoria" id="id_categoria" class="form-select rounded-3" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat['id_categoria'] ?>"><?= esc($cat['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="id_marca" class="form-label text-secondary fw-semibold">Marca *</label>
                            <select name="id_marca" id="id_marca" class="form-select rounded-3" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($marcas as $mar): ?>
                                    <option value="<?= $mar['id_marca'] ?>"><?= esc($mar['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="precio_venta" class="form-label text-secondary fw-semibold">Precio de Venta ($) *</label>
                            <input type="number" step="0.01" min="0.01" name="precio_venta" id="precio_venta" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="stock" class="form-label text-secondary fw-semibold">Stock Inicial *</label>
                            <input type="number" min="0" name="stock" id="stock" class="form-control rounded-3" required>
                        </div>
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
        $('#tablaProductos').DataTable({
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
                    text: "Esta acción eliminará el producto seleccionado.",
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

    function nuevoProducto() {
        $('#id_producto').val('');
        $('#codigo_barras').val('');
        $('#nombre').val('');
        $('#id_categoria').val('');
        $('#id_marca').val('');
        $('#precio_venta').val('');
        $('#stock').val('');
        $('#modalProductoLabel').text('Nuevo Producto');
    }

    function editarProducto(p) {
        $('#id_producto').val(p.id_producto);
        $('#codigo_barras').val(p.codigo_barras || '');
        $('#nombre').val(p.nombre);
        $('#id_categoria').val(p.id_categoria);
        $('#id_marca').val(p.id_marca);
        $('#precio_venta').val(p.precio_venta);
        $('#stock').val(p.stock);
        $('#modalProductoLabel').text('Editar Producto');
        $('#modalProducto').modal('show');
    }
</script>
<?= $this->endSection() ?>