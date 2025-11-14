<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use Softdeletes;

    protected $fillable =
    [
        'nombre',
        'precio',
        'stock',
        'category_id',
        'stockMinimun',
    ];

    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }
}
