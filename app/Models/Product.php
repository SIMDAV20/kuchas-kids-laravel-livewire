<?php

namespace App\Models;

use App\Http\Livewire\Admin\ColorSize;
use App\Traits\ProductScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Product extends Model
{
  use HasFactory;
  use ProductScopes;

  const BORRADOR = 1;
  const PUBLICADO = 2;

  const VARBASE = 'base';
  const VARCOLORS = 'colors';
  const VARSIZES = 'sizes';
  const VARCOLORSSIZES = 'colors_sizes';

  protected $guarded = ['id', 'created_at', 'updated_at'];

  // acesor se puede crear, es parecido a un atributo de un obj
  public function getStockAttribute()
  {
    if ($this->subcategory->size) {
      // verifica la relacion si tiene size y product
      return ColorSize::whereHas('size.product', function (Builder $query) {
        // ids que conincidan con la id del producto
        $query->where('id', $this->id);
      })->sum('quantity');
    } elseif ($this->subcategory->color) {
      return ColorProduct::whereHas('product', function (Builder $query) {
        $query->where('id', $this->id);
      })->sum('quantity');
    } else {
      return $this->quantity;
    }
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

  // Relacion muchos a muchos
  public function colors()
  {
    return $this->belongsToMany(Color::class)->withPivot('quantity', 'status', 'id')->withTimestamps();
  }

  // Relacion muchos a muchos
  public function sizes()
  {
    return $this->belongsToMany(Size::class)
      ->withPivot('quantity', 'status', 'price', 'offer_price', 'offer_date', 'slug', 'id')->withTimestamps();
  }

  // Relacion uno a muchos inversa
  public function color_product()
  {
    return $this->hasMany(ColorProduct::class);
  }

  // Relacion uno a muchos inversa
  public function product_size()
  {
    return $this->hasMany(ProductSize::class);
  }

  public function color_product_size()
  {
    return $this->hasMany(ColorProductSize::class);
  }

  //TODO: a eliminar
  public function images()
  {
    return $this->morphMany(Image::class, "imageable");
  }

  public function image_product(): HasManyThrough
  {
    return $this->hasManyThrough(Image::class, ImageProduct::class, 'product_id', 'id', 'id', 'image_id');
  }

  public function deleteVariants($newValue)
  {
    $config = [
      'base' => [
        'color_product',
        'product_size',
        'color_product_size',
      ],
      'colors' => [
        'product_size',
        'color_product_size',
      ],
      'sizes' => [
        'color_product',
        'color_product_size',
      ],
      'colors_sizes' => [
        'color_product',
        'product_size',
      ],
    ];

    foreach ($config[$newValue] as $key => $value) {
      if (count($this->$value)) {
        $this->$value()->delete();
      }
    }
  }

  public function saveDelete()
  {
    if (count($this->color_product) > 0) {
      $this->color_product()->delete();
    }
    if (count($this->product_size) > 0) {
      $this->product_size()->delete();
    }
    if (count($this->color_product_size) > 0) {
      $this->color_product_size()->delete();
    }
    $this->delete();
  }

  public function getMinPrice()
  {
    $base = 0;
    if (count($this->product_size) > 0) {
      $prices = $this->product_size->where('price', '>', 0)->pluck('price');
      $offer_prices = $this->product_size->where('offer_price', '>', 0)->pluck('offer_price');
      if (count($offer_prices) > 0) {
        $base = $offer_prices->min();
      } else {
        $base = $prices->min();
      }
    } else {
      $base = $this->offer_price > 0 ?: $this->price;
    }

    return $base;
  }

  public function onStockToSell()
  {
    // crea una nueva prop al modelo principal
    if (count($this->color_product) > 0) {
      $this->color_product = $this->color_product()->where('quantity', '>', 0)->get();
      $this->colors = Color::whereIn('id', $this->color_product->pluck('color_id'))->get();
      // $this->images = $this->color_product->images ?? [];
    }
    if (count($this->product_size) > 0) {
      $this->product_size = $this->product_size()->where('quantity', '>', 0)->get();
      $this->sizes = Size::whereIn('id', $this->product_size->pluck('size_id'))->get();
      // $this->images = $this->product_size->images ?? [];
    }
    if (count($this->color_product_size) > 0) {
      //TODO: falta complementar
      $this->color_product_size = $this->color_product_size()->where('quantity', '>', 0)->get();
    }
  }

  // concant the url from base product
  public function getFirstPublicSlug()
  {
    $slug = $this->slug;
    switch ($this->type_variant) {
      case Product::VARCOLORS:
        $slug .= '?c=' . $this->color_product()->where('status', Product::PUBLICADO)->first()->color->slug;
        break;
      case Product::VARSIZES:
        $slug .= '?t=' . $this->product_size()->where('status', Product::PUBLICADO)->first()->size->slug;
        break;
      case Product::VARCOLORSSIZES:
        $query = $this->color_product_size()->where('status', Product::PUBLICADO)->first();
        $slug .= '?c=' . $query->color->slug .
          '&t=' . $query->size->slug;
        break;
    }

    return $slug;
  }

  public function getFirstImageURL()
  {
    $image_prod = $this->image_product()->first();
    return $image_prod ? $image_prod->image->url : null;
  }

  // URL AMIGABLES
  public function getRouteKeyName()
  {
    return 'slug';
  }
}
