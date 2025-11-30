<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeriodoFiscal extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_inicio',
        'fecha_cierre',
        'empresa_id',
        'ejercicio_fiscal',
        'esta_cerrado'
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }
}
