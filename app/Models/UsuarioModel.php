<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuario';
    protected $primaryKey       = 'id_usuario';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombre', 'correo', 'clave', 'rol', 'estado'];

    protected $validationRules = [
        'nombre' => 'required|min_length[3]|max_length[100]',
        'correo' => 'required|valid_email|max_length[100]',
        'rol'    => 'required|in_list[administrador,encargado]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El campo Nombre es obligatorio.'
        ],
        'correo' => [
            'required'    => 'El Correo es obligatorio.',
            'valid_email' => 'Debe ingresar un Correo válido.'
        ],
        'rol' => [
            'required' => 'El campo Rol es obligatorio.',
            'in_list'  => 'El rol seleccionado no es válido.'
        ]
    ];
}