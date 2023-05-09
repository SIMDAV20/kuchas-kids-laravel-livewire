<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    // Relacion de uno a muchos
    public function products() {
        return $this->HasMany(Product::class);
    }

    // Relacion muchos a muchos cartegory->brands
    public function categories() {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }
}
