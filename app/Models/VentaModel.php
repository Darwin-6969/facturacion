<?php
namespace App\Models;

use CodeIgniter\Model;

class VentaModel extends Model
{
    protected $table            = 'venta';
    protected $primaryKey       = 'id_venta';
    protected $allowedFields    = ['fecha', 'id_cliente', 'id_usuario', 'total'];
    protected $useTimestamps   = false;

    // Obtener ventas junto con los datos de cliente y usuario
    public function obtenerVentas()
    {
        return $this->select('venta.*, cliente.nombre as cliente_nombre, cliente.identificacion as cliente_identificacion, usuario.nombre as usuario_nombre')
                    ->join('cliente', 'cliente.id_cliente = venta.id_cliente')
                    ->join('usuario', 'usuario.id_usuario = venta.id_usuario')
                    ->orderBy('venta.id_venta', 'DESC')
                    ->findAll();
    }
}