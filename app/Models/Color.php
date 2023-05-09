<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    // Relacion muchos a muchos
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function color_products()
    {
        return $this->hasMany(ColorProduct::class);
    }

    public function color_product_sizes()
    {
        return $this->hasMany(ColorProductSize::class);
    }
}
