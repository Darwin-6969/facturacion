<?php

namespace App\Controllers;

use App\Models\ProveedorModel;

class ProveedorController extends BaseController
{
    protected $proveedorModel;

    public function __construct()
    {
        $this->proveedorModel = new ProveedorModel();
    }

    public function index()
    {
        $data['proveedores'] = $this->proveedorModel->findAll();
        return view('proveedores/index', $data);
    }

    public function guardar()
    {
        $id = $this->request->getPost('id_proveedor');

        $data = [
            'identificacion' => trim($this->request->getPost('identificacion')),
            'nombre'         => trim($this->request->getPost('nombre')),
            'telefono'       => trim($this->request->getPost('telefono'))
        ];

        if (!empty($id)) {
            $data['id_proveedor'] = $id;
        }

        if (!$this->proveedorModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->proveedorModel->errors());
        }

        $mensaje = empty($id) ? 'Proveedor registrado con éxito.' : 'Proveedor actualizado con éxito.';
        return redirect()->to('proveedores')->with('success', $mensaje);
    }

    public function eliminar($id = null)
    {
        if ($id && $this->proveedorModel->find($id)) {
            $this->proveedorModel->delete($id);
            return redirect()->to('proveedores')->with('success', 'Proveedor eliminado correctamente.');
        }

        return redirect()->to('proveedores')->with('error', 'Proveedor no encontrado.');
    }
}