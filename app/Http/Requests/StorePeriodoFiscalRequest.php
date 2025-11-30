<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePeriodoFiscalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fecha_inicio' => 'required|date',
            'fecha_cierre' => 'required|date|after:fecha_inicio',
            'empresa_id' => 'required|exists:empresas,id',
            'ejercicio_fiscal' => 'required|string|max:45',
            'esta_cerrado' => 'required|boolean',
            // Eliminar 'es_actual' de las reglas de validación
        ];
    }
}
