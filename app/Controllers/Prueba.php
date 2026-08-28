<?php

namespace App\Controllers;

class prueba extends BaseController
{
    public function index(): string
    {
        // echo "Hola";
        $datos["nombre"] = "ABC DARWIN YAMBERLA";
        $datos["direccion"] = "ABC Ibarra";
        return view('prueba/index', $datos);
    }

}
