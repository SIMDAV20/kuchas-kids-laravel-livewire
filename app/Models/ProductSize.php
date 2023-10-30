<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
  use HasFactory;

  const BORRADOR = 1;
  const PUBLICADO = 2;

  protected $table = "product_size";

  protected $guarded = ['id', 'created_at', 'updated_at'];

  // Relacion uno a muchos inversa
  public function size()
  {
    return $this->belongsTo(Size::class);
  }

  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  protected function gallery(): Attribute
  {
    return Attribute::make(
      get: fn ($value) => !is_null($value) ?  json_decode($value) : null,
      set: fn ($value) => !is_null($value) ? json_encode($value) : null,
    );
  }

  // URL AMIGABLES
  public function getRouteKeyName()
  {
    return 'slug';
  }
}
