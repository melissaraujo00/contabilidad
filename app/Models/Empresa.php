<?php

namespace App\Models;

use App\Enums\TipoEmpresa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = [
        'nombre',
        'tipo_empresa'
    ];

    protected function casts(){
        return [
            'tipo_empresa' => TipoEmpresa::class
        ];
    }
}
