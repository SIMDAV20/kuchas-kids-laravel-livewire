<?php

namespace App\Traits;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

trait ProductScopes
{
  // Query Scope

  public function scopeSearch(&$query, $search)
  {
    $columns = [
      'products.slug',
      'products.sku',
      'products.price',
      'products.offer_price',
      'products.description'
    ];

    return $query->where('products.status', Product::PUBLICADO)
      ->when($search, function ($query) use ($search, $columns) {
        $query->where(function ($query) use ($search, $columns) {
          $query->where(DB::raw("CONCAT_WS(''," . implode(',', $columns) . " )"), 'LIKE', "%$search%")
            ->orWhereHas('variants', function ($query) use ($search) {
              $query->where('sku', 'LIKE', "%$search%");
            });
        });
      });
  }

  public function scopeSearchAll(&$query, $search)
  {
    $columns = [
      'products.slug',
      'products.name',
      'products.sku',
      'products.price',
      'products.offer_price',
      'products.description'
    ];
    return $query->when($search, fn ($query) => $query->orWhere(DB::raw("CONCAT_WS(''," . implode(',', $columns) . " )"), 'LIKE', "%$search%"));
  }

  public function scopeName($query, $name)
  {
    if ($name) {
      return $query->orWhere('name', 'LIKE', "%$name%");
    }
  }
}
