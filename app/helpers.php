<?php

use App\Models\Size;
use App\Models\Product;
use App\Models\ColorProduct;
use App\Models\ColorProductSize;
use App\Models\ProductSize;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Mockery\Undefined;

if (!function_exists('current_quantity')) {
  function current_quantity($item_id, $type_variant)
  {
    $model_str = [
      Product::VARBASE => 'Product',
      Product::VARCOLORS => 'ColorProduct',
      Product::VARSIZES => 'ProductSize',
      Product::VARCOLORSSIZES => 'ColorProductSize',
    ][$type_variant];

    $model = '\\App\\Models\\' . $model_str;

    $quantity = $model::find($item_id)->first()->quantity;

    return $quantity;
  }
}

if (!function_exists('qty_added')) {
  function qty_added($item_id, $type_variant)
  {
    $cart = Cart::content();

    $item = $cart->where('id', $item_id)
      ->where('options.type_variant', $type_variant)->first();

    if ($item) {
      return  $item->qty;
    } else {
      return 0;
    }
  }
}


if (!function_exists('qty_available')) {
  function qty_available($item_id, $type_variant)
  {
    return current_quantity($item_id, $type_variant) - qty_added($item_id, $type_variant);
  }
}

if (!function_exists('discount')) {
  function discount($item)
  {
    $product = Product::find($item->id);

    $qty_available = qty_available($item->id, @$item->options->color_id, @$item->options->size_id);

    if ($item->options->color_id) {
      // $product->colors()->attach([
      //     $item->options->color_id => ['quantity' => $qty_available] // agregando otra vez el stock reservado
      // ]);

      $color_product = ColorProduct::where('item_id', $product->id)
        ->where('color_id', $item->options->color_id)->first();
      $color_product->quantity = $qty_available;
      $color_product->save();
    } else if ($item->options->size_id) {
      $product_size = ProductSize::where('item_id', $product->id)
        ->where('size_id', $item->options->size_id)->first();
      $product_size->quantity = $qty_available;
      $product_size->save();
    } else {
      $product->quantity = $qty_available;
      $product->save();
    }
  }
}

if (!function_exists('increase')) {
  function increase($item)
  {
    $product = Product::find($item->id);

    $quantity = current_quantity(
      $item->id,
      @$item->options->color_id ?: null,
      @$item->options->size_id ?: null
    ) + $item->qty; // al hacer json_decode del content de la orden

    if (isset($item->options->color_id)) {
      $color_product = ColorProduct::where('item_id', $product->id)
        ->where('color_id', $item->options->color_id)->first();
      $color_product->quantity = $quantity;
      $color_product->save();
    } else if (isset($item->options->size_id)) {
      $product_size = ProductSize::where('item_id', $product->id)
        ->where('size_id', $item->options->size_id)->first();
      $product_size->quantity = $quantity;
      $product_size->save();
    } else {
      $product->quantity = $quantity;
      $product->save();
    }

    // {
    //     "0061beede5107af13b9f56dbc2556a49": {
    //         "rowId":"0061beede5107af13b9f56dbc2556a49",
    //         "id":34,
    //         "name":"Mandil Azul",
    //         "qty":1,
    //         "price":40,
    //         "weight":550,
    //         "options": {
    //             "size":"Talla S",
    //             "size_id":1,
    //             "image":"http:\/\/127.0.0.1:8000\/storage\/products\/mandil-azul-1.jpg"
    //         },
    //         "discount":0,
    //         "tax":8.4,"subtotal":40
    //     }
    // }

    // if ($item->options->size_id) {
    //     $size = Size::find($item->options->size_id);

    //     $size->colors()->detach($item->options->color_id); // eliminar la relacion

    //     $size->colors()->attach([
    //         $item->options->color_id => ['quantity' => $quantity] // agregando otra vez el stock reservado
    //     ]);
    // } elseif ($item->options->color_id) {

    //     $product->colors()->detach($item->options->color_id); // eliminar la relacion

    //     $product->colors()->attach([
    //         $item->options->color_id => ['quantity' => $quantity] // agregando otra vez el stock reservado
    //     ]);
    // } else {
    //     $product->quantity = $quantity;
    //     $product->save();
    // }
  }
}


if (!function_exists('findProduct')) {
  function findProduct($model_str, $id)
  {
    // switch ($model) {
    //   case 'Product':
    //     $item = Product::findOrFail($id);
    //     break;
    //   case 'ColorProduct':
    //     $item = ColorProduct::findOrFail($id);
    //     break;
    //   case 'ProductSize':
    //     $item = ProductSize::findOrFail($id);
    //     break;
    //   case 'ColorProductSize':
    //     $item = ColorProductSize::findOrFail($id);
    //     break;
    // }


    $model_name = '\\App\\Models\\' . $model_str;
    $model = new $model_name;

    return $model::findOrFail($id);
  }
}


if (!function_exists('applyOffer')) {
  // TODO: FUTURO ANIADIR DICHA OFFERTA POR FECHA
  function applyOffer($var_prod)
  {
    $base_price = null;
    $price = 0;
    if (
      $var_prod->offer_price > 0 &&
      (Carbon::parse($var_prod->offer_date)->format('Y-m-d') >= Carbon::now()->format('Y-m-d')) &&
      $var_prod->offer_date !== null
    ) {
      $price = $var_prod->offer_price;
      $base_price = $var_prod->price;

      // SI LA FECHA LIMITE ES INDEFINIDO
    } else if ($var_prod->offer_price > 0 && $var_prod->offer_date == null) {
      $price = $var_prod->offer_price;
      $base_price = $var_prod->price;
    } else {
      $price = $var_prod->price;
    }

    return [$base_price, $price];
  }
}

if (!function_exists('applyMaxMinPrice')) {
  // TODO: FUTURO ANIADIR DICHA OFFERTA POR FECHA
  function applyMaxMinPrice($product)
  {
    $base_price = null;
    $price = 0;

    switch ($product->type_variant) {
      case Product::VARBASE:
        $base_price = $product->offer_price > 0 ? $product->price : null;
        $price = $product->offer_price > 0 ? $product->offer_price : $product->price;
        break;
      case Product::VARCOLORS:
        [$min, $max] = getMaxMinPrice($product->color_product);
        $base_price = $min ?? null;
        $price = $max;
        break;
      case Product::VARSIZES:
        [$min, $max] = getMaxMinPrice($product->product_size);
        $base_price = $min ?? null;
        $price = $max;
        break;
        // case Product::VARCOLORSSIZES:
        //     $product->single_img =
        //     break;
    }

    return [$base_price, $price];
  }
}

if (!function_exists('getMaxMinPrice')) {
  // TODO: FUTURO ANIADIR DICHA OFFERTA POR FECHA
  function getMaxMinPrice($items)
  {

    $selectCols = collect([]);
    foreach ($items as $item) {
      $selectCols->push($item->offer_price);
      $selectCols->push($item->price);
    }

    $selectCols = $selectCols->flatten();

    $selectCols = $selectCols->reject(function ($value) {
      // Reject if the value is null or zero
      return $value === null || $value === 0;
    });

    $min = $selectCols->min();
    $max = $selectCols->max();

    return [$min, $max];
  }
}
