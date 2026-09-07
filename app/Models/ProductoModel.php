<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $table            = 'producto';
    protected $primaryKey       = 'id_producto';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['codigo_barras', 'nombre', 'id_categoria', 'id_marca', 'precio_venta', 'stock'];

    protected $validationRules = [
        'nombre'       => 'required|min_length[3]|max_length[100]',
        'id_categoria' => 'required|is_natural_no_zero',
        'id_marca'     => 'required|is_natural_no_zero',
        'precio_venta' => 'required|numeric|greater_than[0]',
        'stock'        => 'required|integer|greater_than_equal_to[0]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El Nombre del producto es obligatorio.'
        ],
        'id_categoria' => [
            'required'            => 'Debe seleccionar una Categoría.',
            'is_natural_no_zero' => 'Seleccione una Categoría válida.'
        ],
        'id_marca' => [
            'required'            => 'Debe seleccionar una Marca.',
            'is_natural_no_zero' => 'Seleccione una Marca válida.'
        ],
        'precio_venta' => [
            'required'     => 'El Precio de Venta es obligatorio.',
            'greater_than' => 'El Precio debe ser mayor a 0.'
        ],
        'stock' => [
            'required'              => 'El Stock es obligatorio.',
            'greater_than_equal_to' => 'El Stock no puede ser negativo.'
        ]
    ];

    // Obtener productos con los nombres de Categoría y Marca
    public function getProductos()
    {
        return $this->select('producto.*, categoria.nombre AS categoria, marca.nombre AS marca')
                    ->join('categoria', 'categoria.id_categoria = producto.id_categoria')
                    ->join('marca', 'marca.id_marca = producto.id_marca')
                    ->findAll();
    }
}