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
  function current_quantity($product_id, $color_id = null, $size_id = null)
  {
    $product = Product::find($product_id);

    if ($size_id) {
      $size = Size::find($size_id);
      $quantity = ProductSize::where('size_id', $size->id)->where('product_id', $product_id)->first()->quantity;
    } elseif ($color_id) {
      $quantity = $product->colors->find($color_id)->pivot->quantity;
    } else {
      $quantity  = $product->quantity;
    }

    return $quantity;
  }
}

if (!function_exists('qty_added')) {
  function qty_added($product_id, $color_id = null, $size_id = null)
  {

    $cart = Cart::content();

    // retorna un obj con el first
    $item = $cart->where('id', $product_id)
      ->where('options.color_id', $color_id)
      ->where('options.size_id', $size_id)->first();

    if ($item) {
      return  $item->qty;
    } else {
      return 0;
    }
  }
}


if (!function_exists('qty_available')) {
  function qty_available($product_id, $color_id = null, $size_id = null)
  {
    return current_quantity($product_id, $color_id, $size_id) - qty_added($product_id, $color_id, $size_id);
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

      $color_product = ColorProduct::where('product_id', $product->id)
        ->where('color_id', $item->options->color_id)->first();
      $color_product->quantity = $qty_available;
      $color_product->save();
    } else if ($item->options->size_id) {
      $product_size = ProductSize::where('product_id', $product->id)
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
      $color_product = ColorProduct::where('product_id', $product->id)
        ->where('color_id', $item->options->color_id)->first();
      $color_product->quantity = $quantity;
      $color_product->save();
    } else if (isset($item->options->size_id)) {
      $product_size = ProductSize::where('product_id', $product->id)
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
