<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MarcaModel;

class MarcaController extends BaseController
{
    protected $marcaModel;

    public function __construct()
    {
        $this->marcaModel = new MarcaModel();
    }

    public function index()
    {
        $data['marcas'] = $this->marcaModel->findAll();
        return view('marcas/index', $data);
    }

    public function guardar()
    {
        $id = $this->request->getPost('id_marca');

        $data = [
            'nombre' => trim($this->request->getPost('nombre'))
        ];

        if (!empty($id)) {
            $data['id_marca'] = $id;
        }

        if (!$this->marcaModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->marcaModel->errors());
        }

        $mensaje = empty($id) ? 'Marca registrada con éxito.' : 'Marca actualizada con éxito.';
        return redirect()->to('marcas')->with('success', $mensaje);
    }

    public function eliminar($id)
    {
        try {
            $this->marcaModel->delete($id);
            return redirect()->to('marcas')->with('success', 'Marca eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()->to('marcas')->with('error', 'No se puede eliminar la marca porque está asociada a productos.');
        }
    }
}