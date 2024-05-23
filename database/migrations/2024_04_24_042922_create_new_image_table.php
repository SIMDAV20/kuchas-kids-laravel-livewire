<?php


use App\Models\ColorProduct;
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
        $products = collect(Product::all());

        $backup = collect([]);

        foreach ($products as $product) {
            if (count($product->images)) {
                foreach ($product->images as $image) {
                    $backup->push([
                        'product_id' => $product->id,
                        'image_id' => $image->id,
                        'product_type' => 'App\Models\Product',
                    ]);
                }
            }
        }

        $products = collect(ColorProduct::all());

        foreach ($products as $product) {
            if (count($product->images)) {
                foreach ($product->images as $image) {
                    $backup->push([
                        'product_id' => $product->id,
                        'image_id' => $image->id,
                        'product_type' => 'App\Models\ColorProduct',
                    ]);
                }
            }
        }

        $products = collect(ProductSize::all());

        foreach ($products as $product) {
            if (count($product->images)) {
                foreach ($product->images as $image) {
                    $backup->push([
                        'product_id' => $product->id,
                        'image_id' => $image->id,
                        'product_type' => 'App\Models\ProductSize',
                    ]);
                }
            }
        }

        foreach ($backup as $image) {
            DB::table('images')->insert([
                'product_id' => $image['product_id'],
                'image_id' => $image['image_id'],
                'product_type' => $image['product_type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Schema::table('images', function (Blueprint $table) {
        //     $table->dropColumn('imageable_id');
        //     $table->dropColumn('imageable_type');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_image');
    }
};
