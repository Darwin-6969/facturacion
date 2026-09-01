<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Administración de Categorías
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Categorías</h3></div>
            <div class="col-sm-6 text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCategoria" onclick="limpiarModal()">
                    <i class="bi bi-plus-circle"></i> Nueva Categoría
                </button>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <?php if (session()->has('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->has('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-header">
                <div class="row justify-content-end">
                    <div class="col-md-4">
                        <input type="text" id="filtroTabla" class="form-control" placeholder="Filtrar categorías...">
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped align-middle" id="tablaCategorias">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Nombre</th>
                            <th style="width: 100px" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($categorias)): ?>
                            <?php foreach ($categorias as $key => $cat): ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= esc($cat['nombre']) ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning" onclick='editarCategoria(<?= json_encode($cat) ?>)' title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <a href="<?= base_url('categorias/eliminar/' . $cat['id_categoria']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar esta categoría?')" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center py-3">No hay categorías registradas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalCategoria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('categorias/guardar') ?>" method="post" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitulo">Nueva Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_categoria" id="id_categoria">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de la Categoría</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('filtroTabla').addEventListener('keyup', function() {
        let filtro = this.value.toLowerCase();
        let filas = document.querySelectorAll('#tablaCategorias tbody tr');
        filas.forEach(fila => {
            let texto = fila.textContent.toLowerCase();
            fila.style.display = texto.includes(filtro) ? '' : 'none';
        });
    });

    function limpiarModal() {
        document.getElementById('modalTitulo').innerText = 'Nueva Categoría';
        document.getElementById('id_categoria').value = '';
        document.getElementById('nombre').value = '';
    }

    function editarCategoria(cat) {
        document.getElementById('modalTitulo').innerText = 'Editar Categoría';
        document.getElementById('id_categoria').value = cat.id_categoria;
        document.getElementById('nombre').value = cat.nombre;
        var myModal = new bootstrap.Modal(document.getElementById('modalCategoria'));
        myModal.show();
    }
</script>
<?= $this->endSection() ?>