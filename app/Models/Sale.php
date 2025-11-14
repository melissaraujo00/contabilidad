<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'numero_documento',
        'fecha',
        'subtotal',
        'iva',
        'customer_id',
        'documentType_id'
    ];

    public function DocumentType():BelongsTo{
        return $this->belongsTo( DocumentType::class)->withTrashed();
    }

    public function Customer():BelongsTo
    {
        return $this->belongsTo( Customer::class)->withTrashed();
    }

    public function SalesDetails():HasMany
    {
        return $this->hasMany( SaleDetails::class)->withTrashed();
    }

}
