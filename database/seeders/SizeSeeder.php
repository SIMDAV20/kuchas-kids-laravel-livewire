<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Size;
use Illuminate\Support\Str;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Builder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sizes = [
            [
                'name' => 'Talla S',
                'slug' => Str::slug('Talla S')
            ],
            [
                'name' => 'Talla M',
                'slug' => Str::slug('Talla M')
            ],
        ];

        foreach ($sizes as $key => $size) {
            Size::create([
                'name' => $size['name'],
                'slug' => $size['slug']
            ]);
        }

        // $products = Product::whereHas('subcategory', function (Builder $query) {
        //     $query->where('color', true)
        //             ->where('size', true);
        // })->get();

        // $sizes = ['Talla S', 'Talla M', 'Talla L'];

        // foreach ($products as $product) {

        //     foreach ($sizes as $size) {
        //         $product->sizes()->create([
        //             'name' => $size
        //         ]);
        //     }
        // }
    }
}
