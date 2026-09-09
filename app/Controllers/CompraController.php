<?php

namespace App\Controllers;

use App\Models\CompraModel;
use App\Models\DetalleCompraModel;
use App\Models\ProductoModel;
use App\Models\ProveedorModel;

class CompraController extends BaseController
{
    public function index()
    {
        $compraModel = new CompraModel();
        
        $data['compras'] = $compraModel->select('compra.*, proveedor.nombre as proveedor, usuario.nombre as usuario')
                                      ->join('proveedor', 'proveedor.id_proveedor = compra.id_proveedor')
                                      ->join('usuario', 'usuario.id_usuario = compra.id_usuario', 'left')
                                      ->orderBy('compra.fecha', 'DESC')
                                      ->findAll();

        return view('compras/index', $data);
    }

    public function nueva()
    {
        $proveedorModel = new ProveedorModel();
        $productoModel  = new ProductoModel();

        $data['proveedores'] = $proveedorModel->findAll();
        $data['productos']   = $productoModel->findAll();

        return view('compras/nueva', $data);
    }

    public function guardar()
    {
        $json = $this->request->getJSON();

        if (empty($json->id_proveedor) || empty($json->items) || count($json->items) === 0) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'Seleccione un proveedor y agregue productos']);
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $compraModel   = new CompraModel();
            $detalleModel  = new DetalleCompraModel();
            $productoModel = new ProductoModel();

            // 1. Cabecera de Compra
            $dataCompra = [
                'id_proveedor' => $json->id_proveedor,
                'id_usuario'   => session()->get('id_usuario') ?? 1,
                'total'        => $json->total,
                'fecha'        => date('Y-m-d H:i:s')
            ];
            
            $id_compra = $compraModel->insert($dataCompra);
            
            if (!$id_compra) {
                throw new \Exception("Error al registrar la cabecera de la compra: " . implode(', ', $compraModel->errors()));
            }

            // 2. Procesar Productos e Inventario
            foreach ($json->items as $item) {
                $idProducto = $item->id_producto;

                if (empty($idProducto) || $idProducto == 0 || $item->es_nuevo) {
                    // Datos para el Producto Nuevo (incluye campos genéricos para evitar fallos NOT NULL)
                    $nuevoProducto = [
                        'nombre'       => trim($item->nombre),
                        'precio_venta' => $item->precio_venta,
                        'stock'        => $item->cantidad,
                        'precio_costo' => $item->costo_unitario ?? 0.00,
                        'id_categoria' => 1, // Categoría por defecto
                        'id_marca'     => 1, // Marca por defecto
                        'estado'       => 1
                    ];
                    
                    $idProducto = $productoModel->insert($nuevoProducto);

                    if (!$idProducto) {
                        $erroresProd = implode(', ', $productoModel->errors() ?? []);
                        throw new \Exception("Error al crear el producto '{$item->nombre}': " . ($erroresProd ?: 'Consulte los campos requeridos en la BD'));
                    }
                } else {
                    // Producto Existente: Aumentar stock
                    $productoActual = $productoModel->find($idProducto);
                    
                    if ($productoActual) {
                        $nuevoStock = (int)$productoActual['stock'] + (int)$item->cantidad;
                        $productoModel->update($idProducto, [
                            'stock' => $nuevoStock
                        ]);
                    } else {
                        throw new \Exception("No se encontró el producto ID: " . $idProducto);
                    }
                }

                // 3. Detalle de Compra
                $dataDetalle = [
                    'id_compra'      => $id_compra,
                    'id_producto'    => $idProducto,
                    'cantidad'       => $item->cantidad,
                    'costo_unitario' => $item->costo_unitario,
                    'subtotal'       => $item->subtotal
                ];

                $resDetalle = $detalleModel->insert($dataDetalle);

                if (!$resDetalle) {
                    throw new \Exception("Error al registrar el detalle de la compra para el producto ID: " . $idProducto);
                }
            }

            if ($db->transStatus() === FALSE) {
                $errorBD = $db->error();
                $db->transRollback();
                return $this->response->setJSON([
                    'status' => 'error', 
                    'msg'    => 'Error de Base de Datos (' . $errorBD['code'] . '): ' . $errorBD['message']
                ]);
            }

            $db->transCommit();
            return $this->response->setJSON(['status' => 'success', 'msg' => 'Compra registrada e inventario actualizado correctamente']);

        } catch (\Throwable $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }
}