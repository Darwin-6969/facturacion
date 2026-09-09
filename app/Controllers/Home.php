<?php

namespace App\Controllers;

use App\Models\VentaModel;
use App\Models\ProductoModel;
use App\Models\ClienteModel;

class Home extends BaseController
{
    public function index()
    {
        return view('dashboard/index');
    }

    public function obtenerMetricasAjax()
    {
        $db = \Config\Database::connect();
        $ventaModel = new VentaModel();
        $productoModel = new ProductoModel();
        $clienteModel = new ClienteModel();

        // 1. Tarjetas KPI
        // Ventas registradas el día de hoy
        $ventasHoy = $ventaModel->where('DATE(fecha) = CURDATE()')->countAllResults();
        
        // Ingresos acumulados del mes actual
        $queryIngresos = $db->query("
            SELECT COALESCE(SUM(total), 0) as total 
            FROM venta 
            WHERE MONTH(fecha) = MONTH(CURRENT_DATE()) 
              AND YEAR(fecha) = YEAR(CURRENT_DATE())
        ")->getRowArray();
        $ingresosMes = $queryIngresos['total'] ?? 0;

        // Total clientes registrados y productos en stock crítico (<= 5)
        $totalClientes = $clienteModel->countAllResults();
        $stockCritico = $productoModel->where('stock <=', 5)->countAllResults();

        // 2. Gráfico: Actividad de los últimos 7 días
        $grafico7Dias = $db->query("
            SELECT 
                DATE_FORMAT(dias.fecha, '%d/%m') as fecha_formato,
                COALESCE(COUNT(v.id_venta), 0) as total_ventas,
                COALESCE(SUM(v.total), 0) as total_ingresos
            FROM (
                SELECT CURDATE() - INTERVAL (a.a + b.a*10) DAY as fecha
                FROM (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a
                CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b
            ) dias
            LEFT JOIN venta v ON DATE(v.fecha) = dias.fecha
            WHERE dias.fecha BETWEEN CURDATE() - INTERVAL 6 DAY AND CURDATE()
            GROUP BY dias.fecha
            ORDER BY dias.fecha ASC
        ")->getResultArray();

        // 3. Productos más vendidos (Top 5)
        $topProductos = $db->query("
            SELECT 
                p.nombre,
                SUM(dv.cantidad) as unidades,
                SUM(dv.subtotal) as ingresos
            FROM detalle_venta dv
            INNER JOIN producto p ON p.id_producto = dv.id_producto
            GROUP BY p.id_producto, p.nombre
            ORDER BY unidades DESC
            LIMIT 5
        ")->getResultArray();

        // 4. Alertas de Inventario (Productos con bajo stock)
        $alertasStock = $productoModel->where('stock <=', 5)
            ->orderBy('stock', 'ASC')
            ->findAll(5);

        return $this->response->setJSON([
            'status' => 'success',
            'kpi' => [
                'ventas_hoy'    => $ventasHoy,
                'ingresos_mes'  => number_format((float)$ingresosMes, 2, '.', ','),
                'clientes'      => $totalClientes,
                'stock_critico' => $stockCritico
            ],
            'grafico_7dias' => $grafico7Dias,
            'top_productos' => $topProductos,
            'alertas_stock' => $alertasStock
        ]);
    }
}