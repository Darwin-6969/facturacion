<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class AuthController extends BaseController
{
    public function index()
    {
        // Si ya está autenticado, redirigir según su rol
        if (session()->get('isLoggedIn')) {
            if (session()->get('rol') === 'encargado') {
                return redirect()->to(base_url('facturas'));
            }
            return redirect()->to(base_url('dashboard'));
        }
        
        return view('auth/login');
    }

    public function authenticate()
    {
        $correo   = $this->request->getPost('correo');
        $password = $this->request->getPost('password');

        $usuarioModel = new UsuarioModel();
        $user         = $usuarioModel->where('correo', $correo)
                                     ->where('estado', 1) // Solo usuarios activos
                                     ->first();

        if ($user && password_verify($password, $user['clave'])) {
            // Guardar datos en la sesión
            session()->set([
                'id_usuario' => $user['id_usuario'],
                'nombre'     => $user['nombre'],
                'correo'     => $user['correo'],
                'rol'        => $user['rol'],
                'isLoggedIn' => true,
            ]);

            // Redirigir según el rol del usuario
            if ($user['rol'] === 'encargado') {
                return redirect()->to(base_url('facturas'));
            }

            return redirect()->to(base_url('dashboard'));
        }

        return redirect()->back()->with('error', 'Credenciales incorrectas o usuario inactivo.');
    }

    public function logout()
    {
        // Destruir la sesión activa
        session()->destroy();

        // Cargar la vista auth/logout.php
        return view('auth/logout');
    }
}