<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
        'nombre' => [
            'required',
            'string',
            'max:50',
        ],

        'precio' => [
            'required',
            'numeric',
            'decimal:2',
        ],

        'stock' => [
            'required',
            'integer',
            'min:0',
        ],

        'category_id' => [
            'required',
            'exists:categories,id',
        ],

        'stockMinimun' => [
            'required',
            'integer',
            'min:0',
        ],
    ];
}

}
