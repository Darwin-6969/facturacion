<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="content-wrapper p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-bold mb-0"><i class="fas fa-shopping-bag me-2"></i>Gestión de Compras</h3>
            <small class="text-muted">Registro de compras y abastecimiento de inventario.</small>
        </div>
        <a href="<?= base_url('compras/nueva') ?>" class="btn btn-primary fw-bold">
            <i class="fas fa-plus me-1"></i> Registrar Nueva Compra
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0 table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>N° Compra</th>
                        <th>Proveedor</th>
                        <th>Registrado por</th>
                        <th>Fecha</th>
                        <th class="text-end">Total ($)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($compras)): ?>
                        <?php foreach ($compras as $c): ?>
                            <tr>
                                <td><strong>#<?= str_pad($c['id_compra'], 6, '0', STR_PAD_LEFT) ?></strong></td>
                                <td><?= $c['proveedor'] ?? 'Sin Proveedor' ?></td>
                                <td><?= $c['usuario'] ?? 'Sistema' ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($c['fecha'])) ?></td>
                                <td class="text-end fw-bold text-success">$<?= number_format($c['total'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No se han registrado compras aún.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>