<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Relacion de uno a muchos
    public function products() {
        return $this->hasMany(Product::class);  
    }

    // Relacion de uno a muchos inversa
    public function category() {
        return $this->belongsTo(Category::class);
    }

    // Query Scope
    public function scopeName($query, $name) {
        if ($name) {
            return $query->orWhere('name', 'LIKE', "%$name%");
        }
    }

}
