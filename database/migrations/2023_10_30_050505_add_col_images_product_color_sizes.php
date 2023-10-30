<?php

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
      $table->json('gallery')->nullable()->after('slug');
    });
    Schema::table('color_product', function (Blueprint $table) {
      $table->json('gallery')->nullable()->after('slug');
    });
    Schema::table('product_size', function (Blueprint $table) {
      $table->json('gallery')->nullable()->after('slug');
    });
    Schema::table('color_product_size', function (Blueprint $table) {
      $table->json('gallery')->nullable()->after('slug');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    //
  }
};
