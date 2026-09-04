<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class UsuarioController extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    public function index()
    {
        $data['usuarios'] = $this->usuarioModel->findAll();
        return view('usuarios/index', $data);
    }

    public function guardar()
    {
        $id = $this->request->getPost('id_usuario');
        $clave = $this->request->getPost('clave');

        $data = [
            'nombre' => trim($this->request->getPost('nombre')),
            'correo' => trim($this->request->getPost('correo')),
            'rol'    => $this->request->getPost('rol'),
            'estado' => $this->request->getPost('estado') ?? 1
        ];

        if (!empty($id)) {
            $data['id_usuario'] = $id;
            if (!empty($clave)) {
                $data['clave'] = password_hash($clave, PASSWORD_BCRYPT);
            }
        } else {
            if (empty($clave)) {
                return redirect()->back()->withInput()->with('errors', ['clave' => 'La contraseña es obligatoria para nuevos usuarios.']);
            }
            $data['clave'] = password_hash($clave, PASSWORD_BCRYPT);
        }

        if (!$this->usuarioModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->usuarioModel->errors());
        }

        $mensaje = empty($id) ? 'Usuario registrado con éxito.' : 'Usuario actualizado con éxito.';
        return redirect()->to('usuarios')->with('success', $mensaje);
    }

    public function eliminar($id = null)
    {
        if ($id && $this->usuarioModel->find($id)) {
            $this->usuarioModel->delete($id);
            return redirect()->to('usuarios')->with('success', 'Usuario eliminado correctamente.');
        }

        return redirect()->to('usuarios')->with('error', 'Usuario no encontrado.');
    }
}