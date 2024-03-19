<?php

use App\Models\Product;
use App\Models\Subcategory;
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
            $table->smallInteger('position')->nullable()->after('slug');
        });

        $subcategories = Subcategory::all();

        foreach ($subcategories as $subcat) {
            $prods = $subcat->products;

            $prods->each(function ($prod, $index) {
                $prod->position = $index + 1;
                $prod->save();
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
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
