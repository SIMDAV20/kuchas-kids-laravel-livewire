<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ColorProduct extends Model
{
  use HasFactory;

  const BORRADOR = 1;
  const PUBLICADO = 2;

  protected $table = "color_product";

  protected $guarded = ['id', 'created_at', 'updated_at'];

  protected $with = ['image_product'];

  // Relacion uno a muchos inversa
  public function color()
  {
    return $this->belongsTo(Color::class);
  }

  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  public function image_product(): HasManyThrough
  {
    return $this->hasManyThrough(Image::class, ImageProduct::class, 'product_id', 'id', 'id', 'image_id');
  }

  public function getFirstImageURL()
  {
    $image_prod = $this->image_product()->first();
    return $image_prod ? $image_prod->url : null;
  }

  // URL AMIGABLES
  public function getRouteKeyName()
  {
    return 'slug';
  }
}
