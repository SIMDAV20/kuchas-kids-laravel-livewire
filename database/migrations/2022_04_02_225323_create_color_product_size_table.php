<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColorProductSizeTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    if (!Schema::hasTable('color_product_size')) {
      Schema::create('color_product_size', function (Blueprint $table) {
        $table->id();
        $table->string('sku')->nullable();
        $table->string('slug')->unique();

        $table->integer('quantity')->default(1);
        // $table->decimal('price');
        // $table->decimal('offer_price')->nullable();
        // $table->string('offer_date')->nullable();
        $table->smallInteger('position')->nullable();

        $table->unsignedBigInteger('color_id');
        $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade');

        $table->unsignedBigInteger('size_id');
        $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');

        $table->unsignedBigInteger('product_id');
        $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

        $table->timestamps();
      });
    }
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('color_product_size');
  }
}
