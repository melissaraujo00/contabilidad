<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleDetails extends Model
{
    public $fillable = [
        'precio',
        'subtotal',
        'iva',
        'monto',
        'sale_id',
        'product_id'
    ];

    public function Sale(): BelongsTo
    {
        return $this->belongsTo( Sale::class)->withTrashed();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo( Product::class)->withTrashed();
    }

}
