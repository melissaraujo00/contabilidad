<?php

namespace App\Models;

use App\Enums\TipoEmpresa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'tipo_empresa'
    ];

    protected function casts(){
        return [
            'tipo_empresa' => TipoEmpresa::class
        ];
    }
    public function PeriodoFiscales():HasMany {
        return $this->hasMany(PeriodoFiscal::class, 'empresa_id'
    );
    }
}
