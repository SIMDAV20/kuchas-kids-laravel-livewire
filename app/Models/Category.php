<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'image', 'position']; // , 'icon'

    // Relacion uno a muchos
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    // Relacion muchos a muchos
    public function brands()
    {
        return $this->belongsToMany(Brand::class);
    }

    public function products()
    {
        // a traves de otra tabla
        return $this->hasManyThrough(Product::class, Subcategory::class);
    }

    // URL AMIGABLES
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public static function getLastPosition()
    {
        return Category::all()->count() + 1;
    }
}
