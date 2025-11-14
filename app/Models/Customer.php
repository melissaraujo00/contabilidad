<?php

namespace App\Models;

use App\Enums\CustomerType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Sale;


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

    public function Sales():HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
