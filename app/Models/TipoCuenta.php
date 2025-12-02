<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoCuenta extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * @return HasMany<CatalogoCuenta,TipoCuenta>
     */
    public function catalogoCuentas(): HasMany
    {
        return $this->hasMany(CatalogoCuenta::class, 'tipo_cuenta_id');
    }
}
