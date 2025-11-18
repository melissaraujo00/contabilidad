<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Sale;

class Diary extends Model
{
<<<<<<< HEAD:app/Models/DocumentType.php
    protected $fillable = ['nombre'];

    public function Sales():HasMany
    {
        return $this->hasMany(Sale::class);
    }
=======
    /** @use HasFactory<\Database\Factories\DiaryFactory> */
    use HasFactory;
>>>>>>> main:app/Models/Diary.php
}
