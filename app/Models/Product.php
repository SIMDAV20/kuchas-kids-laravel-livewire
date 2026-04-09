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

  protected $casts = [
      'images' => 'array',
  ];

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
  public function subcategory()
  {
    return $this->belongsTo(Subcategory::class);
  }

  // Relacion uno a muchos polimórfica (LEGACY - Se usará el campo JSON 'images' en su lugar)
  public function images_relations()
  {
    return $this->morphMany(Image::class, "imageable");
  }

  /**
   * Obtiene los modelos de imagen reales basados en el array de IDs en 'images'.
   * Si el producto base no tiene imágenes asignadas, recolecta las de sus variantes.
   */
  public function getAssignedImagesAttribute()
  {
      if (!empty($this->images)) {
          return Image::whereIn('id', $this->images)
              ->get()
              ->sortBy(fn($model) => array_search($model->id, $this->images))
              ->values();
      }

      // Fallback: collect unique image IDs from all variants
      $variantImageIds = $this->variants()
          ->pluck('images')
          ->filter()
          ->flatMap(fn($ids) => $ids)
          ->unique()
          ->values()
          ->all();

      if (empty($variantImageIds)) return collect();

      return Image::whereIn('id', $variantImageIds)->get();
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
