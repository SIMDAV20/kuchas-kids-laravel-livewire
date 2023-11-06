<?php

use App\Models\ColorProduct;
use App\Models\ImageProduct;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    $product_simples = Product::all();
    $color_products = ColorProduct::all();
    $product_sizes = ProductSize::all();

    $products = collect([...$product_simples, ...$color_products, ...$product_sizes]);

    $gallery = [];

    $records = [];

    foreach ($products as $key => $prod) {
      if ($prod->images->count() > 0) {

        $items = [];

        foreach ($prod->images as $key => $img) {
          $image = [
            'url' => $img->url,
            'created_at' => $img->created_at,
            'updated_at' => $img->updated_at
          ];
          $gallery[] = $image;
          $items[] = $img->url;
        }

        $prod->gallery = json_encode($items);
        $prod->save();
      }
    }
    DB::table('image_products')->insert($gallery);
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    ImageProduct::query()->delete();
    Product::query()->update(['gallery' => null]);
    ColorProduct::query()->update(['gallery' => null]);
    ProductSize::query()->update(['gallery' => null]);
  }
};
