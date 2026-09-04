<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClienteModel;

class ClienteController extends BaseController
{
    protected $clienteModel;

    public function __construct()
    {
        $this->clienteModel = new ClienteModel();
    }

    public function index()
    {
        $data['clientes'] = $this->clienteModel->findAll();
        return view('clientes/index', $data);
    }

    public function guardar()
    {
        $id = $this->request->getPost('id_cliente');

        $data = [
            'identificacion' => trim($this->request->getPost('identificacion')),
            'nombre'         => trim($this->request->getPost('nombre')),
            'telefono'       => trim($this->request->getPost('telefono')),
            'correo'         => trim($this->request->getPost('correo'))
        ];

        if (!empty($id)) {
            $data['id_cliente'] = $id;
        }

        if (!$this->clienteModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->clienteModel->errors());
        }

        $mensaje = empty($id) ? 'Cliente registrado con éxito.' : 'Cliente actualizado con éxito.';
        return redirect()->to('clientes')->with('success', $mensaje);
    }

    public function eliminar($id)
    {
        try {
            $this->clienteModel->delete($id);
            return redirect()->to('clientes')->with('success', 'Cliente eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->to('clientes')->with('error', 'No se puede eliminar el cliente porque tiene ventas asociadas.');
        }
    }
}