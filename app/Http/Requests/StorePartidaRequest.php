<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePartidaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'partida_numero' => ['nullable', 'integer'],
            'fecha_partida' => ['required', 'date'],
            'periodo_fiscal_id' => ['required', 'exists:periodo_fiscals,id'],
            'tipo_partida' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'estado' => ['nullable', 'boolean'],

            // Validaciones para detalles
            'detalles' => ['required', 'array', 'min:1'],
            'detalles.*.catalogo_cuenta_id' => ['required'],
            'detalles.*.tipo_movimiento' => ['nullable', 'in:DEBE,HABER'],
            'detalles.*.monto_debe' => ['nullable', 'numeric', 'min:0'],
            'detalles.*.monto_haber' => ['nullable', 'numeric', 'min:0'],
            'detalles.*.parcial' => ['nullable', 'numeric', 'min:0'],
            'detalles.*.orden' => ['required', 'integer', 'min:1'],
            'detalles.*.observaciones' => ['nullable', 'string'],
        ];
    }
}
