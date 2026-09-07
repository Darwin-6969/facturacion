<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\CategoriaModel;
use App\Models\MarcaModel;

class ProductoController extends BaseController
{
    protected $productoModel;
    protected $categoriaModel;
    protected $marcaModel;

    public function __construct()
    {
        $this->productoModel  = new ProductoModel();
        $this->categoriaModel = new CategoriaModel();
        $this->marcaModel     = new MarcaModel();
    }

    public function index()
    {
        $data['productos']  = $this->productoModel->getProductos();
        $data['categorias'] = $this->categoriaModel->findAll();
        $data['marcas']     = $this->marcaModel->findAll();

        return view('productos/index', $data);
    }

    public function guardar()
    {
        $id = $this->request->getPost('id_producto');

        $data = [
            'codigo_barras' => trim($this->request->getPost('codigo_barras')),
            'nombre'        => trim($this->request->getPost('nombre')),
            'id_categoria'  => $this->request->getPost('id_categoria'),
            'id_marca'      => $this->request->getPost('id_marca'),
            'precio_venta'  => $this->request->getPost('precio_venta'),
            'stock'         => $this->request->getPost('stock')
        ];

        if (!empty($id)) {
            $data['id_producto'] = $id;
        }

        if (!$this->productoModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->productoModel->errors());
        }

        $mensaje = empty($id) ? 'Producto registrado con éxito.' : 'Producto actualizado con éxito.';
        return redirect()->to('productos')->with('success', $mensaje);
    }

    public function eliminar($id = null)
    {
        if ($id && $this->productoModel->find($id)) {
            $this->productoModel->delete($id);
            return redirect()->to('productos')->with('success', 'Producto eliminado correctamente.');
        }

        return redirect()->to('productos')->with('error', 'Producto no encontrado.');
    }
}