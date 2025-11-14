<?php

namespace App\Models;

use App\Enums\CustomerType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Customer extends Model
{
    protected $fillable = [
        'nombre',
        'apellido',
        'telefono',
        'email',
        'nit',
        'direccion',
        'tipo_cliente'
    ];

    protected $casts = [
        'tipo_cliente' => CustomerType::class,
    ];
}
