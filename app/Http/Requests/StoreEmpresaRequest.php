<?php

namespace App\Http\Requests;

use App\Enums\TipoEmpresa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreEmpresaRequest extends FormRequest
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
            'nombre' => ['required', 'string', 'max:100'],
            'tipo_empresa' => ['required', new Enum(TipoEmpresa::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la empresa es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no debe exceder los 100 caracteres.',
            'tipo_empresa.required' => 'El tipo de empresa es obligatorio.',
            'tipo_empresa.string' => 'El tipo de empresa debe ser texto.',
            'tipo_empresa.enum' => 'El tipo de empresa seleccionado no es válido.'
        ];
    }
}
