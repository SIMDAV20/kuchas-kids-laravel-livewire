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
      'name',
      'sku',
      'price',
      'offer_price',
      'description'
    ];
    return $query->when($search, fn ($query) => $query->orWhere(DB::raw("CONCAT_WS(''," . implode(',', $columns) . " )"), 'LIKE', "%$search%"))
      ->where('status', Product::PUBLICADO);
  }

  public function scopeName($query, $name)
  {
    if ($name) {
      return $query->orWhere('name', 'LIKE', "%$name%");
    }
  }
  // public function scopeSku($query, $sku)
  // {
  //   if ($sku) {
  //     return $query->orWhere('sku', 'LIKE', "%$sku%");
  //   }
  // }
  // public function scopePrice($query, $price)
  // {
  //   if ($price) {
  //     return $query->orWhere('price', 'LIKE', "%$price%");
  //   }
  // }
  // public function scopeOfferPrice($query, $offer_price)
  // {
  //   if ($offer_price) {
  //     return $query->orWhere('offer_price', 'LIKE', "%$offer_price%");
  //   }
  // }
  // public function scopeDescription($query, $description)
  // {
  //   if ($description) {
  //     return $query->orWhere('description', 'LIKE', "%$description%");
  //   }
  // }
}
