<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
    Schema::table('products', function (Blueprint $table) {
      $table->string('type_variant')->after('slug')->default(Product::VARBASE);
    });


    $products = Product::all();

    foreach ($products as $key => $prod) {
      $type = '';
      if (count($prod->color_product) > 0) {
        $type = 'colors';
      } else if (count($prod->product_size) > 0) {
        $type = 'sizes';
      } else if (count($prod->color_product_size) > 0) {
        $type = 'colors_sizes';
      } else {
        $type = 'base';
      }

      $prod->type_variant = $type;
      $prod->save();
    }
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('products', function (Blueprint $table) {
      $table->dropColumn('type_variant');
    });
  }
};
