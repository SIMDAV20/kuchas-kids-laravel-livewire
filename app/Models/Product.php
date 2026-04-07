<?php

namespace App\Models;

use App\Traits\ProductScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
  use HasFactory;
  use ProductScopes;

  const BORRADOR = 1;
  const PUBLICADO = 2;

  protected $guarded = ['id', 'created_at', 'updated_at'];

  // acesor se puede crear, es parecido a un atributo de un obj
  public function getStockAttribute()
  {
      if ($this->variants()->count() > 0) {
          return $this->variants()->sum('stock');
      }
      return $this->quantity ?? 0;
  }

  // Relacion uno a muchos inversa
  public function brand()
  {
    return $this->belongsTo(Brand::class);
  }

  // Relacion uno a muchos inversa
  public function ages()
  {
    return $this->belongsTo(Age::class);
  }

  public function age_product()
  {
    return $this->hasMany(AgeProduct::class);
  }

  // Relacion uno a muchos inversa
  public function subcategory()
  {
    return $this->belongsTo(Subcategory::class);
  }



  // Relacion uno a muchos polimórfica
  public function images()
  {
    return $this->morphMany(Image::class, "imageable");
  }

  public function deleteVariants()
  {
      $this->variants()->delete();
  }

  public function saveDelete()
  {
      $this->deleteVariants();
      $this->delete();
  }

  public function getMinPrice()
  {
      if ($this->variants()->count() > 0) {
          $prices = $this->variants()->where('price', '>', 0)->pluck('price');
          $offer_prices = $this->variants()->where('offer_price', '>', 0)->pluck('offer_price');
          
          if ($offer_prices->count() > 0) {
              return $offer_prices->min();
          } elseif ($prices->count() > 0) {
              return $prices->min();
          }
      }
      
      return $this->offer_price > 0 ? $this->offer_price : $this->price;
  }

  public function onStockToSell()
  {
      // Precargar variantes con stock disponible y activas
      $this->load(['variants' => function($query) {
          $query->where('stock', '>', 0)->where('status', true);
      }]);
  }

  public function flashOffer()
  {
    return $this->morphOne(FlashOffer::class, 'offerable');
  }

  public function variants()
  {
    return $this->hasMany(ProductVariant::class);
  }

  // URL AMIGABLES
  public function getRouteKeyName()
  {
    return 'slug';
  }
}
