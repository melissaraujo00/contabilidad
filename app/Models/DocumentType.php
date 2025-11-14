<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Sale;

class DocumentType extends Model
{
    protected $fillable = ['nombre'];

    public function Sales():HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
