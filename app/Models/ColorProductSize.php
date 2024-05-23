<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ColorProductSize extends Model
{
  use HasFactory;

  const BORRADOR = 1;
  const PUBLICADO = 2;

  protected $table = "color_product_size";

  protected $guarded = ['id', 'created_at', 'updated_at'];

  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  public function color()
  {
    return $this->belongsTo(Color::class);
  }

  public function size()
  {
    return $this->belongsTo(Size::class);
  }

  public function images(): HasManyThrough
  {
    return $this->hasManyThrough(Image::class, ImageProduct::class, 'product_id', 'id', 'id', 'image_id');
  }

  // URL AMIGABLES
  public function getRouteKeyName()
  {
    return 'slug';
  }
}
