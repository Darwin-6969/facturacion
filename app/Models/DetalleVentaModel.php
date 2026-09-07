<?php
namespace App\Models;

use CodeIgniter\Model;

class DetalleVentaModel extends Model
{
    protected $table            = 'detalle_venta';
    protected $primaryKey       = 'id_detalle_venta';
    protected $allowedFields    = ['id_venta', 'id_producto', 'cantidad', 'precio_unitario', 'subtotal'];
    protected $useTimestamps   = false;

    // Obtener detalles de una factura específica
    public function obtenerDetallesPorVenta($idVenta)
    {
        return $this->select('detalle_venta.*, producto.nombre as producto_nombre, producto.codigo_barras')
                    ->join('producto', 'producto.id_producto = detalle_venta.id_producto')
                    ->where('detalle_venta.id_venta', $idVenta)
                    ->findAll();
    }
}