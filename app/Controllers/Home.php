<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // Si el usuario es encargado, se redirige al módulo de facturación
        if (session()->get('rol') === 'encargado') {
            return redirect()->to(base_url('facturas'));
        }

        // Si es administrador, se asigna el título y la vista del dashboard
        $data = [
            'title' => 'Dashboard',
            'view'  => 'prueba/index' // O la vista principal de tu dashboard dentro de app/Views/
        ];

        // Se renderiza usando el layout principal de tu sistema
        return view('layouts/main', $data);
    }
}