<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura #<?= $venta['id_venta'] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { font-family: sans-serif; padding: 20px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print mb-3">
        <button onclick="window.print()" class="btn btn-primary">Imprimir / Guardar PDF</button>
        <a href="<?= base_url('facturas') ?>" class="btn btn-secondary">Volver</a>
    </div>

    <div class="card p-4">
        <div class="d-flex justify-content-between">
            <div>
                <h3>FACTURA</h3>
                <p><strong>Nº:</strong> <?= str_pad($venta['id_venta'], 6, '0', STR_PAD_LEFT) ?></p>
                <p><strong>Fecha:</strong> <?= $venta['fecha'] ?? date('Y-m-d H:i:s') ?></p>
            </div>
            <div class="text-end">
                <p><strong>Cliente:</strong> <?= esc($venta['cliente_nombre']) ?></p>
                <p><strong>Identificación:</strong> <?= esc($venta['identificacion']) ?></p>
                <p><strong>Atendido por:</strong> <?= esc($venta['usuario_nombre']) ?></p>
            </div>
        </div>

        <hr>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unit.</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($detalles)): ?>
                    <?php foreach ($detalles as $d): ?>
                        <tr>
                            <td><?= esc($d['producto_nombre'] ?? $d['nombre'] ?? 'Producto') ?></td>
                            <td><?= $d['cantidad'] ?></td>
                            <td>$<?= number_format($d['precio_unitario'], 2) ?></td>
                            <td>$<?= number_format($d['subtotal'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="row text-end mt-4">
            <div class="col-6 offset-6">
                <h4>Total: $<?= number_format($venta['total'], 2) ?></h4>
            </div>
        </div>
    </div>
</body>
</html>