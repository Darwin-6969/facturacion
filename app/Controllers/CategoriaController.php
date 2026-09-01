<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoriaModel;

class CategoriaController extends BaseController
{
    protected $categoriaModel;

    public function __construct()
    {
        $this->categoriaModel = new CategoriaModel();
    }

    public function index()
    {
        $data['categorias'] = $this->categoriaModel->findAll();
        return view('categorias/index', $data);
    }

    public function guardar()
    {
        $id = $this->request->getPost('id_categoria');
        
        $data = [
            'nombre' => trim($this->request->getPost('nombre'))
        ];

        if (!empty($id)) {
            $data['id_categoria'] = $id;
        }

        if (!$this->categoriaModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->categoriaModel->errors());
        }

        $mensaje = empty($id) ? 'Categoría registrada con éxito.' : 'Categoría actualizada con éxito.';
        return redirect()->to('categorias')->with('success', $mensaje);
    }

    public function eliminar($id)
    {
        try {
            $this->categoriaModel->delete($id);
            return redirect()->to('categorias')->with('success', 'Categoría eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()->to('categorias')->with('error', 'No se puede eliminar la categoría porque está asociada a productos.');
        }
    }
}