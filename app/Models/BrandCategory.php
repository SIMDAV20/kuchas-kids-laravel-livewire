<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandCategory extends Model
{
    use HasFactory;

    // Relacion de uno a muchos inversa
    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    // Relacion de uno a muchos inversa
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
