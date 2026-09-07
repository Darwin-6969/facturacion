<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\VentaModel;
use App\Models\DetalleVentaModel;
use App\Models\ClienteModel;
use App\Models\ProductoModel;

class FacturacionController extends BaseController
{
    protected $ventaModel;
    protected $detalleVentaModel;
    protected $clienteModel;
    protected $productoModel;

    public function __construct()
    {
        $this->ventaModel        = new VentaModel();
        $this->detalleVentaModel = new DetalleVentaModel();
        $this->clienteModel      = new ClienteModel();
        $this->productoModel     = new ProductoModel();
    }

    // Historial de Facturas
    public function index()
    {
        $data['facturas'] = $this->ventaModel->obtenerVentas();
        return view('facturacion/index', $data);
    }

    // Vista de Formulario Maestro-Detalle
    public function nueva()
    {
        return view('facturacion/nueva');
    }

    // AJAX: Buscar Clientes
    public function buscarCliente()
    {
        $term = $this->request->getGet('q');
        $clientes = $this->clienteModel
            ->like('identificacion', $term)
            ->orLike('nombre', $term)
            ->findAll(10);

        return $this->response->setJSON($clientes);
    }

    // AJAX: Buscar Productos con Stock disponible
    public function buscarProducto()
    {
        $term = $this->request->getGet('q');
        $productos = $this->productoModel
            ->groupStart()
                ->like('codigo_barras', $term)
                ->orLike('nombre', $term)
            ->groupEnd()
            ->where('stock >', 0)
            ->findAll(10);

        return $this->response->setJSON($productos);
    }

    // AJAX: Ver detalle de una factura grabada
    public function verDetalle($idVenta)
    {
        $venta = $this->ventaModel->select('venta.*, cliente.nombre as cliente_nombre, cliente.identificacion, usuario.nombre as usuario_nombre')
                                  ->join('cliente', 'cliente.id_cliente = venta.id_cliente')
                                  ->join('usuario', 'usuario.id_usuario = venta.id_usuario')
                                  ->where('venta.id_venta', $idVenta)
                                  ->first();

        if (!$venta) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Factura no encontrada.']);
        }

        $detalles = $this->detalleVentaModel->obtenerDetallesPorVenta($idVenta);

        return $this->response->setJSON([
            'status' => 'success',
            'venta' => $venta,
            'detalles' => $detalles
        ]);
    }

    // Procesar Venta / Transacción
    public function guardar()
    {
        $db = \Config\Database::connect();
        $db->transStart(); // Inicio de transacción para garantizar atomicidad

        try {
            $idCliente = $this->request->getPost('id_cliente');
            $idUsuario = session()->get('id_usuario') ?? 1; // ID de usuario logueado
            $productos = $this->request->getPost('productos');

            if (empty($idCliente) || empty($productos) || !is_array($productos)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Debe seleccionar un cliente y al menos un producto.']);
            }

            $subtotalGeneral = 0;
            $itemsAProcesar = [];

            // Validación rigurosa de Stock en la BDD antes de guardar
            foreach ($productos as $p) {
                $prodDB = $this->productoModel->find($p['id_producto']);

                if (!$prodDB) {
                    $db->transRollback();
                    return $this->response->setJSON(['status' => 'error', 'message' => 'El producto seleccionado ya no existe.']);
                }

                if ($prodDB['stock'] < $p['cantidad']) {
                    $db->transRollback();
                    return $this->response->setJSON([
                        'status'  => 'error', 
                        'message' => "Stock insuficiente para: {$prodDB['nombre']}. Stock disponible: {$prodDB['stock']}"
                    ]);
                }

                $sub = $p['cantidad'] * $prodDB['precio_venta'];
                $subtotalGeneral += $sub;

                $itemsAProcesar[] = [
                    'id_producto'     => $p['id_producto'],
                    'cantidad'        => $p['cantidad'],
                    'precio_unitario' => $prodDB['precio_venta'],
                    'subtotal'        => $sub,
                    'stock_actual'    => $prodDB['stock']
                ];
            }

            // Impuestos (IVA 15%) y Total Final
            $iva = $subtotalGeneral * 0.15;
            $totalFinal = $subtotalGeneral + $iva;

            // 1. Insertar Cabecera (tabla: venta)
            $idVenta = $this->ventaModel->insert([
                'id_cliente' => $idCliente,
                'id_usuario' => $idUsuario,
                'total'      => $totalFinal
            ]);

            // 2. Insertar Detalle (tabla: detalle_venta) y Descontar Stock (tabla: producto)
            foreach ($itemsAProcesar as $item) {
                $this->detalleVentaModel->insert([
                    'id_venta'        => $idVenta,
                    'id_producto'     => $item['id_producto'],
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal'        => $item['subtotal']
                ]);

                // Actualizar inventario (Disminución de Stock)
                $nuevoStock = $item['stock_actual'] - $item['cantidad'];
                $this->productoModel->update($item['id_producto'], ['stock' => $nuevoStock]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'No se pudo registrar la venta debido a un fallo en la base de datos.']);
            }

            return $this->response->setJSON(['status' => 'success', 'message' => 'Factura generada exitosamente.']);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}