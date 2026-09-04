<?php
namespace App\Models;

use CodeIgniter\Model;

class MarcaModel extends Model
{
    protected $table         = 'marca';
    protected $primaryKey    = 'id_marca';
    protected $allowedFields = ['nombre'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'id_marca' => 'permit_empty|integer',
        'nombre'   => 'required|min_length[2]|max_length[50]|is_unique[marca.nombre,id_marca,{id_marca}]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required'   => 'El nombre de la marca es obligatorio.',
            'min_length' => 'El nombre debe tener al menos 2 caracteres.',
            'max_length' => 'El nombre no puede superar los 50 caracteres.',
            'is_unique'  => 'Esta marca ya existe.'
        ]
    ];
}