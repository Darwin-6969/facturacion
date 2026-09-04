<?php

namespace App\Models;

use CodeIgniter\Model;

class ProveedorModel extends Model
{
    protected $table            = 'proveedor';
    protected $primaryKey       = 'id_proveedor';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['identificacion', 'nombre', 'telefono'];

    // Reglas de validación
    protected $validationRules = [
        'identificacion' => 'required|max_length[20]',
        'nombre'         => 'required|min_length[3]|max_length[100]',
        'telefono'       => 'permit_empty|max_length[20]'
    ];
}