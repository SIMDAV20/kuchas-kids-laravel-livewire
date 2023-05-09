<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Relacion muchos a muchos
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function product_size()
    {
        return $this->hasMany(ProductSize::class);
    }
}
