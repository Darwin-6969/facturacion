<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class AuthController extends BaseController
{
    public function index()
    {
        // Si ya está autenticado, redirigir al módulo principal
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('facturacion'));
        }
        return view('auth/login');
    }

    public function authenticate()
    {
        $correo   = trim($this->request->getPost('username')); // 'username' del formulario se evalúa contra 'correo'
        $clave = $this->request->getPost('password');

        if (empty($correo) || empty($clave)) {
            return redirect()->back()->with('error', 'Por favor, ingrese el correo y la contraseña.');
        }

        $usuarioModel = new UsuarioModel();
        
        // Buscar el usuario por su correo electrónico
        $usuario = $usuarioModel->where('correo', $correo)->first();

        // Validar existencia de usuario, verificación de contraseña cifrada y estado activo
        if ($usuario && password_verify($clave, $usuario['clave'])) {
            
            if (!$usuario['estado']) {
                return redirect()->back()->with('error', 'El usuario se encuentra inactivo.');
            }

            // Guardar variables necesarias en la sesión
            session()->set([
                'id_usuario' => $usuario['id_usuario'],
                'nombre'     => $usuario['nombre'],
                'correo'     => $usuario['correo'],
                'rol'        => $usuario['rol'],
                'isLoggedIn' => true
            ]);

            return redirect()->to(base_url('facturacion'));
        }

        return redirect()->back()->with('error', 'Correo o contraseña incorrectos.');
    }

    public function logout()
    {
        session()->destroy();

        return view('auth/logout');
    }
}