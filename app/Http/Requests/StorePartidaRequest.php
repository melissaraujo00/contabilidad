<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePartidaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'partida_numero' => [
                'required',
                'integer'
            ],
            'fecha_partida' => [
                'required',
                'date'
            ],
            'tipo_partida' => [
                'required'
            ],
            'periodo_fiscal_id' => [
                'required',
            ],
            'description' => [
                'nullable',
                'string'
            ],
            'estado' => [
                'boolean'
            ]
        ];
    }
}
