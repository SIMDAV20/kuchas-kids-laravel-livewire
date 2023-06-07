<?php

use App\Models\Color;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $products = Product::has('color_product')->get();
        // $products = ;

        // dd($products[0]->color_product);

        foreach ($products as $key => $prod) {

            foreach ($prod->color_product as $key => $color_prod) {

                $color = Color::where('id', $color_prod->color_id)->first();
                $slug = Str::slug($prod->name . '-' . $color->name);

                $color_prod->slug = $slug;
                $color_prod->save();
            }
        }
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
