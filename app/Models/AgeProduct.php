<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgeProduct extends Model
{
    use HasFactory;

    protected $table = "age_product";

    // Relacion de uno a muchos inversa
    public function age()
    {
        return $this->belongsTo(Age::class);
    }

    // Relacion de uno a muchos inversa
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
