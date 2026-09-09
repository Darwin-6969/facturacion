<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="content-wrapper p-3">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-bold mb-0">Decisiones con datos claros</h3>
            <small class="text-muted">Revisa el pulso del negocio y detecta lo que necesita atención hoy.</small>
        </div>
        <span class="badge bg-white text-dark border p-2 shadow-sm"><i class="fas fa-calendar-alt me-1"></i> <?= date('d/m/Y') ?></span>
    </div>

    <!-- TARJETAS KPI -->
    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-info"><i class="fas fa-shopping-cart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Ventas de hoy</span>
                    <span class="info-box-number" id="kpi-ventas-hoy">0</span>
                    <small class="text-muted">transacciones registradas</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success"><i class="fas fa-dollar-sign"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Ingresos del mes</span>
                    <span class="info-box-number">$<span id="kpi-ingresos-mes">0.00</span></span>
                    <small class="text-muted">acumulado mensual</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Clientes registrados</span>
                    <span class="info-box-number" id="kpi-clientes">0</span>
                    <small class="text-muted">base de clientes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Stock por revisar</span>
                    <span class="info-box-number" id="kpi-stock-critico">0</span>
                    <small class="text-muted">productos con ≤ 5 unidades</small>
                </div>
            </div>
        </div>
    </div>

    <!-- GRÁFICOS -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header border-0">
                    <h5 class="card-title fw-bold">Actividad de los últimos 7 días</h5>
                </div>
                <div class="card-body">
                    <canvas id="chart7Dias" style="min-height: 280px; height: 280px; max-height: 280px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLAS DE DETALLE -->
    <div class="row">
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header border-0">
                    <h5 class="card-title fw-bold">Productos más vendidos</h5>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-valign-middle">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Unidades</th>
                                <th class="text-end">Ingresos</th>
                            </tr>
                        </thead>
                        <tbody id="tbl-top-productos">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header border-0">
                    <h5 class="card-title fw-bold text-danger"><i class="fas fa-box me-1"></i> Alertas de Inventario</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush" id="list-alertas-stock">
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- LIBRERÍAS DE JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let chartInstance = null;

function cargarDashboard() {
    $.ajax({
        url: '<?= base_url("metricas-ajax") ?>',
        method: 'GET',
        dataType: 'json',
        success: function(res) {
            if (res.status === 'success') {
                // 1. Actualizar Tarjetas KPI
                $('#kpi-ventas-hoy').text(res.kpi.ventas_hoy);
                $('#kpi-ingresos-mes').text(res.kpi.ingresos_mes);
                $('#kpi-clientes').text(res.kpi.clientes);
                $('#kpi-stock-critico').text(res.kpi.stock_critico);

                // 2. Actualizar Gráfico con Eje Y Doble
                const labels = res.grafico_7dias.map(item => item.fecha_formato);
                const dataVentas = res.grafico_7dias.map(item => item.total_ventas);
                const dataIngresos = res.grafico_7dias.map(item => item.total_ingresos);

                if (chartInstance) {
                    chartInstance.destroy();
                }
                
                const ctx = document.getElementById('chart7Dias').getContext('2d');
                chartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Ingresos ($)',
                                data: dataIngresos,
                                borderColor: '#007bff',
                                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                                fill: true,
                                tension: 0.3,
                                yAxisID: 'y'
                            },
                            {
                                label: 'Ventas (Transacciones)',
                                data: dataVentas,
                                borderColor: '#28a745',
                                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                                fill: false,
                                tension: 0.3,
                                yAxisID: 'y1'
                            }
                        ]
                    },
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        scales: {
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                title: {
                                    display: true,
                                    text: 'Ingresos ($)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value;
                                    }
                                }
                            },
                            y1: {
                                type: 'linear',
                                display: true,
                                position: 'right',
                                title: {
                                    display: true,
                                    text: 'N° Transacciones'
                                },
                                beginAtZero: true,
                                grid: {
                                    drawOnChartArea: false
                                },
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });

                // 3. Renderizar Tabla "Productos más vendidos"
                let htmlTop = '';
                if (res.top_productos && res.top_productos.length > 0) {
                    res.top_productos.forEach(p => {
                        htmlTop += `
                            <tr>
                                <td>${p.nombre}</td>
                                <td class="text-center"><span class="badge bg-info">${p.unidades}</span></td>
                                <td class="text-end fw-bold">$${parseFloat(p.ingresos).toFixed(2)}</td>
                            </tr>`;
                    });
                } else {
                    htmlTop = '<tr><td colspan="3" class="text-center text-muted">Aún no se registran ventas</td></tr>';
                }
                $('#tbl-top-productos').html(htmlTop);

                // 4. Renderizar Alertas de Inventario
                let htmlStock = '';
                if (res.alertas_stock && res.alertas_stock.length > 0) {
                    res.alertas_stock.forEach(s => {
                        htmlStock += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="d-block">${s.nombre}</strong>
                                    <small class="text-muted">Precio: $${parseFloat(s.precio_venta).toFixed(2)}</small>
                                </div>
                                <span class="badge bg-danger rounded-pill">${s.stock} unid.</span>
                            </li>`;
                    });
                } else {
                    htmlStock = '<li class="list-group-item text-center text-muted">Stock en niveles normales</li>';
                }
                $('#list-alertas-stock').html(htmlStock);
            }
        },
        error: function(err) {
            console.error("Error al cargar las métricas AJAX:", err);
        }
    });
}

// Cargar al iniciar la vista y actualizar cada 10 segundos
$(document).ready(function() {
    cargarDashboard();
    setInterval(cargarDashboard, 10000); 
});
</script>
<?= $this->endSection() ?>