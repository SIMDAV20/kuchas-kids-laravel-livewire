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
    // public function up()
    // {
    //     Schema::create('product_variant', function (Blueprint $table) {
    //         $table->id();
    //         $table->string('sku')->nullable();
    //         $table->string('name');
    //         $table->string('slug')->unique();

    //         $table->integer('quantity')->nullable();
    //         $table->decimal('price')->nullable();
    //         $table->decimal('offer_price')->nullable();
    //         $table->string('offer_date')->nullable();

    //         $table->smallInteger('position')->nullable();
    //         $table->enum('status', [Product::BORRADOR, Product::PUBLICADO])->default(Product::BORRADOR); // 1 Y 2

    //         $table->foreignId('product_id')->references('id')->on('products')->onDelete('cascade');
    //         $table->foreignId('color_id')->nullable()->constrained()->onDelete('set null');
    //         $table->foreignId('size_id')->nullable()->constrained()->onDelete('set null');

    //         $table->timestamps();
    //     });
    // }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variant');
    }
};
