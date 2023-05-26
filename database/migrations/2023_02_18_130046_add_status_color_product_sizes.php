<?php

use App\Models\ColorProduct;
use App\Models\ColorProductSize;
use App\Models\ProductSize;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusColorProductSizes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('color_product', function (Blueprint $table) {
            $table->enum('status', [ColorProduct::BORRADOR, ColorProduct::PUBLICADO])->default(ColorProduct::PUBLICADO)->after('slug'); // 1 Y 2
        });

        Schema::table('product_size', function (Blueprint $table) {
            $table->enum('status', [ProductSize::BORRADOR, ProductSize::PUBLICADO])->default(ProductSize::PUBLICADO)->after('slug'); // 1 Y 2
        });

        Schema::table('color_product_size', function (Blueprint $table) {
            $table->enum('status', [ColorProductSize::BORRADOR, ColorProductSize::PUBLICADO])->default(ColorProductSize::PUBLICADO)->after('slug'); // 1 Y 2
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
}
