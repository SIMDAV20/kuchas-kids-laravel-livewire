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

    if (is_null($var_product)) {
      return view('errors.404');
    }



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
      if ($product->status == Product::BORRADOR) {
        return view('errors.404');
      }
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


    // START SEO

    $seoItems = collect([]);
    $subcategory = $product->subcategory;
    if (!empty($subcategory->keywords)) $seoItems->push(json_decode($subcategory->keywords));

    $seoItems->push($subcategory->name);
    $seoItems->push($subcategory->category->name);
    $seoItems->push($product->brand->name);

    // $html = $product->description;
    // $seoItems[] = strip_tags($html);

    $description = '';
    $seoItems = $seoItems->flatten()->unique()->values();
    foreach ($seoItems as $key => $seoItem) {
      $description .= $seoItem . ($key === array_key_last($seoItems->toArray()) ? '' : ',');
    }

    setSEOTools($product->name, $description);

    // END SEO

    return view(
      'products.show',
      compact('base', 'colors', 'sizes', 'var_product', 'product', 'images', 'main_vars', 'price', 'offer_price')
    );
  }
}
