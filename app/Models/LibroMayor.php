<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibroMayor extends Model
{
    /** @use HasFactory<\Database\Factories\LibroMayorFactory> */
    use HasFactory;
    protected $fillable = [
        'catalogo_cuenta_id',
        'peridos_fiscales_id'
    ];
}
