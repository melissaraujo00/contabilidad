<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CatalogoCuenta extends Model
{
    protected $fillable = [
        'codigo',
        'cuenta',
        'tipo_cuenta_id',
        'cuenta_padre_id',
        'esta_activo'
    ];

    /**
     * @return BelongsTo<TipoCuenta,CatalogoCuenta>
     */
    public function tipoCuenta(): BelongsTo
    {
        return $this->belongsTo(TipoCuenta::class);
    }

    /**
     * @return BelongsTo<CatalogoCuenta,CatalogoCuenta>
     */
    public function cuentaPadre(): BelongsTo
    {
        return $this->belongsTo(CatalogoCuenta::class, 'cuenta_padre_id');
    }

    /**
     * @return HasMany<CatalogoCuenta,CatalogoCuenta>
     */
    public function subcuentas(): HasMany
    {
        return $this->hasMany(CatalogoCuenta::class, 'cuenta_padre_id');
    }

    public function partidaDetalles()
    {
        return $this->hasMany(PartidaDetalle::class);
    }
}
