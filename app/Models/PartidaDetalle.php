<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartidaDetalle extends Model
{
    protected $fillable = [
        'partida_id',
        'catalogo_cuenta_id',
        'description',
        'tipo_movimiento',
        'monto',
        'parcial',
        'orden',
        'observaciones'
    ];

    /**
     * @return BelongsTo<Partida,PartidaDetalle>
     */
    public function partida(): BelongsTo
    {
        return $this->belongsTo(Partida::class);
    }
}
