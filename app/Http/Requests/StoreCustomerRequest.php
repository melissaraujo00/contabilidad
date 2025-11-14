<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            'nombre'       => 'required|string|max:50',
            'apellido'     => 'required|string|max:50',
            'telefono'     => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:80',
            'nit'          => 'nullable|string|max:20',
            'direccion'    => 'nullable|string|max:150',
            'tipo_cliente' => ['required', 'in:consumidor,juridico,proveedor'],
        ];
    }
}
