<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Product;
use App\Models\ColorProduct;
use App\Models\Size;
use Carbon\Carbon;
use Countable;
use Illuminate\Http\Request;

class ProductController extends Controller
{

  public function show(string $slug)
  {
    $product = Product::where('slug', $slug)->first();

    return view('products.show', compact('product'));
  }

  public function showProduct(Request $request)
  {
    $models = ['Product', 'ColorProduct', 'ProductSize', 'ColorProductSize'];
    $var_product = null;

    foreach ($models as $key => $model) {
      $model_name = '\\App\\Models\\' . $model;
      $base = $model;
      $var_product = $model_name::where('slug', $request->{'slugProduct'})->first();
      if (!is_null($var_product)) {
        break;
      }
    }

    if (is_null($var_product)) return view('errors.404');

    $images = collect([]);
    $main_vars = collect([]);

    if ($base !== 'Product') {
      $product = $var_product->product;

      switch ($base) {
        case 'ColorProduct':
          $main_vars['main_color'] = $var_product->color_id;
          break;
        case 'ProductSize':
          $main_vars['main_size'] = $var_product->size_id;
          break;
        case 'ColorProductSize':
          $main_vars['main_color'] = $var_product->color_id;
          $main_vars['main_size'] = $var_product->size_id;
          break;
      }
    } else {
      $product = $var_product;
      $product->onStockToSell();

      // verificar por 2da vez que no tiene variantes
      $slug = '';
      do {
        if (count($product->colors) > 0 &&  count($product->sizes) == 0) {
          $slug = $product->color_product->first()->slug;
          break;
        } else if (count($product->sizes) > 0 && count($product->colors) == 0) {
          $slug = $product->product_size->first()->slug;
          break;
        } else if (count($product->colors) > 0 && count($product->sizes) > 0) {
          $slug = $product->color_product_size->first()->slug;
          break;
        }
      } while (false);

      if ($slug != '') {
        return redirect()->route('products.show', ['slugProduct' => $slug]);
      }
    }

    // GET PRICES
    if ($base == 'Product' || $base == 'ColorProduct') {
      $price = $product->price;
      $offer_price = $product->offer_price;
    } else {
      $price = $var_product->price;
      $offer_price = $var_product->offer_price;
    }

    // GET IMAGES
    if ($var_product->images->count() > 0) {
      $images->push(...$var_product->images);
    } else {
      $images->push(...$product->images);
    }

    $colors = $product->colors;
    $sizes = $product->sizes;

    // return compact('base', 'colors', 'images', 'sizes', 'var_product', 'product');

    return view(
      'products.show',
      compact('base', 'colors', 'sizes', 'var_product', 'product', 'images', 'main_vars', 'price', 'offer_price')
    );

    // $config = [
    //     'color_product',
    //     'product_size',
    //     'colors_sizes',
    // ];

    // $colors = [];
    // $sizes = [];

    // foreach ($config as $key => $value) {
    //     $property = $product->{$value};
    //     if (is_array($property) || $property instanceof Countable) {
    //         $count = count($property);
    //         if ($count > 0) {
    //             $vars = $product->{$value}()->where('quantity', '>', 0)->get();
    //             $colors = Color::whereIn('id', @$vars->pluck('color_id'))->get();
    //             $sizes = Size::whereIn('id', @$vars->pluck('size_id'))->get();
    //             break;
    //         }
    //     }
    // }

    // // return [$product, $colors, $sizes];

    // if (count($colors) && isset($color_url)) {
    // }

    // if ($color_url == null) {
    //     # code...
    // }
    // $color = '';

    // $date1 = Carbon::parse($product->offer_date)->format('Y-m-d');
    // $date2 = Carbon::yesterday()->format('Y-m-d');
    // $date3 = Carbon::now()->format('Y-m-d');


    // $showOfferDate = false;
    // $sameDay       = false;
    // if ($date1 > $date2) {
    //     $showOfferDate = true;
    // }

    // if ($date1 == $date2) {
    //     $sameDay = true;
    // }


    // if (isset($color_url)) {
    //     $color = Color::where('slug', $color_url)->first();
    //     return view(
    //         'products.show',
    //         compact('product', 'showOfferDate', 'sameDay', 'color')
    //     );
    // } else if (!isset($color_url)) {
    //     $color = $product->colors()->first();
    //     return view(
    //         'products.show',
    //         compact('product', 'showOfferDate', 'sameDay', 'color')
    //     );
    // } else {
    //     return view(
    //         'products.show',
    //         compact('product', 'showOfferDate', 'sameDay')
    //     );
    // }



  }
}
