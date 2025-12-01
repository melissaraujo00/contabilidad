<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    protected $fillable = [
        'partida_numero',
        'periodo_fiscal_id',
        'fecha_partida',
        'tipo_partida',
        'description',
        'estado',
        'total_debe',
        'total_haber'
    ];

     public function detalles()
    {
        return $this->hasMany(PartidaDetalle::class);
    }
}
