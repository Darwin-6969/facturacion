<?php
namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table         = 'cliente';
    protected $primaryKey    = 'id_cliente';
    protected $allowedFields = ['identificacion', 'nombre', 'telefono', 'correo'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'id_cliente'     => 'permit_empty|integer',
        'identificacion' => 'required|min_length[10]|max_length[13]|is_unique[cliente.identificacion,id_cliente,{id_cliente}]|validar_identificacion',
        'nombre'         => 'required|min_length[3]|max_length[100]',
        'telefono'       => 'permit_empty|max_length[20]',
        'correo'         => 'permit_empty|valid_email|max_length[100]'
    ];

    protected $validationMessages = [
        'identificacion' => [
            'required'               => 'La identificación es obligatoria.',
            'min_length'             => 'La identificación debe tener al menos 10 dígitos.',
            'max_length'             => 'La identificación no debe superar 13 dígitos.',
            'is_unique'              => 'Esta identificación ya está registrada.',
            'validar_identificacion' => 'El número de cédula ingresado no es válido para Ecuador.'
        ],
        'nombre' => [
            'required'   => 'El nombre del cliente es obligatorio.',
            'min_length' => 'El nombre debe tener al menos 3 caracteres.',
            'max_length' => 'El nombre no puede superar los 100 caracteres.'
        ],
        'correo' => [
            'valid_email' => 'Ingrese una dirección de correo electrónico válida.'
        ]
    ];

    /**
     * Algoritmo de validación de Cédula Ecuatoriana (Módulo 10)
     */
    public static function validarCedulaEcuador(string $cedula): bool
    {
        // Verificar que tenga exactamente 10 dígitos numéricos
        if (!ctype_digit($cedula) || strlen($cedula) !== 10) {
            return false;
        }

        $codigoProvincia = (int) substr($cedula, 0, 2);
        // Provincias válidas en Ecuador: 1-24 y 30 (extranjeros)
        if (($codigoProvincia < 1 || $codigoProvincia > 24) && $codigoProvincia !== 30) {
            return false;
        }

        $tercerDigito = (int) substr($cedula, 2, 1);
        if ($tercerDigito >= 6) { // Para cédulas de personas naturales debe ser menor a 6
            return false;
        }

        $coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
        $digitoVerificador = (int) substr($cedula, 9, 1);
        $suma = 0;

        for ($i = 0; $i < 9; $i++) {
            $valor = (int) $cedula[$i] * $coeficientes[$i];
            if ($valor >= 10) {
                $valor -= 9;
            }
            $suma += $valor;
        }

        $totalSuma = $suma % 10;
        $digitoObtenido = ($totalSuma === 0) ? 0 : 10 - $totalSuma;

        return $digitoObtenido === $digitoVerificador;
    }
}