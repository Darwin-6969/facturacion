<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function saludo($nombre,$apellido){
        echo "Hola " . $nombre . " " . $apellido;
    }

    public function sumita($n1, $n2){
              $suma = $n1 + $n2;
              echo "El resultado es: $suma";
    }
}
