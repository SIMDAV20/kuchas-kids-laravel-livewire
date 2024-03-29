<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
  use HasFactory;

  const NO_PUBLIC = 0;
  const PUBLIC = 1;

  protected $guarded = ['id', 'created_at', 'updated_at'];

  // Relacion de uno a muchos
  public function products()
  {
    return $this->hasMany(Product::class);
  }

  public function getProductsCountAttribute()
  {
    return $this->products()->where('status', Product::PUBLICADO)->count();
  }

  // Relacion de uno a muchos inversa
  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  // Query Scope
  public function scopeName($query, $name)
  {
    if ($name) {
      return $query->orWhere('name', 'LIKE', "%$name%");
    }
  }
}
