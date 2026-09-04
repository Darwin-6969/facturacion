<?php
namespace App\Validation;

use App\Models\ClienteModel;

class CustomRules
{
    public function validar_identificacion(string $str, string &$error = null): bool
    {
        // Si tiene 10 dígitos, valida cédula
        if (strlen($str) === 10) {
            return ClienteModel::validarCedulaEcuador($str);
        }
        
        // Si tiene 13 dígitos (RUC de persona natural)
        if (strlen($str) === 13 && str_ends_with($str, '001')) {
            return ClienteModel::validarCedulaEcuador(substr($str, 0, 10));
        }

        return false;
    }
}