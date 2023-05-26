<?php

namespace Database\Seeders;

use App\Models\Age;
use App\Models\Brand;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

use function PHPUnit\Framework\isEmpty;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return => '<i class="fas fa-mobile-alt"></i>'void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Alimentación',
                'slug' => Str::slug('alimentacion'),
                'position' => 2,
                'subcategories' => [
                    [
                        'name' => 'Plato de Silicona con succión',
                        'slug' => Str::slug('Plato de Silicona con succión'),
                        // 'color' => true
                    ], // 1
                    [
                        'name' => 'Plato de silicona con succión y cuchara',
                        'slug' => Str::slug('Plato de silicona con succión y cuchara'),
                        // 'color' => true
                    ], // 2
                    [
                        'name' => 'Babero de silicona',
                        'slug' => Str::slug('Babero de silicona'),
                        // 'color' => true
                    ], // 3
                ],
            ], // 1
            [
                'name' => 'Juguetes',
                'slug' => Str::slug('Juguetes'),
                'position' => 3,
                'subcategories' => [
                    [
                        'name' => 'Sonajeros zoo',
                        'slug' => Str::slug('Sonajeros zoo'),
                    ], // 4
                    [
                        'name' => 'Sonajeros vehículos',
                        'slug' => Str::slug('Sonajeros vehículos'),
                    ], // 5
                    [
                        'name' => 'Sonajeros pies y manos',
                        'slug' => Str::slug('Sonajeros pies y manos'),
                    ], // 6
                    [
                        'name' => 'Libro de tela sensorial',
                        'slug' => Str::slug('Libro de tela sensorial'),
                    ], // 7
                    [
                        'name' => 'Muñecos Sensoriales',
                        'slug' => Str::slug('Muñecos Sensoriales'),
                    ], // 8

                ],
            ], // 2
            [
                'name' => 'Actividades',
                'slug' => Str::slug('Actividades'),
                'position' => 1,
                'subcategories' => [
                    [
                        'name' => 'Mandil impermeable recreativo (3 a 7 años)',
                        'slug' => Str::slug('Mandil impermeable recreativo (3 a 7 años)'),
                    ], // 9
                    [
                        'name' => 'Babero Mandil impermeable arte / pintura (11 meses a 3 años)',
                        'slug' => Str::slug('Babero Mandil impermeable arte / pintura (11 meses a 3 años)'),
                    ], // 10
                ],
            ], // 2
            [
                'name' => 'Seguridad',
                'position' => 4,
                'slug' => Str::slug('Seguridad'),
                'subcategories' => [
                    [
                        'name' => 'Cadena Sujetador',
                        'slug' => Str::slug('Cadena Sujetador'),
                    ], // 11
                    [
                        'name' => 'Rodilleras',
                        'slug' => Str::slug('Rodilleras'),
                    ], // 12
                ],
            ], // 3
            // [
            //     'name' => 'Peluches Musicales',
            //     'slug' => Str::slug('Peluches Musicales'),
            //     'age'  => true,
            // ],
        ];

        $brands = [
            [
                'name' => 'BEBEK-BABY',
                'slug' => Str::slug('BEBEK-BABY'),
            ],
            [
                'name' => 'JollyBaby',
                'slug' => Str::slug('JollyBaby'),
            ],
            [
                'name' => 'Sozzy',
                'slug' => Str::slug('Sozzy'),
            ],
            [
                'name' => 'Happy Monkey',
                'slug' => Str::slug('Happy Monkey'),
            ],
        ];

        $ages = [
            // [
            //     'name' => '1 a 3 años',
            //     'slug' => Str::slug('1 a 3 años')
            // ],
            [
                'name' => '3 a 5 años',
                'slug' => Str::slug('3 a 5 años')
            ],
            [
                'name' => '5 a 7 años',
                'slug' => Str::slug('5 a 7 años')
            ],
        ];

        foreach ($categories as $category) {
            $new_category = Category::create([
                'name'     => $category['name'],
                'slug'     => $category['slug'],
                'position' => $category['position'],
                'image'    => 'categories/' . $category['slug'] . '.jpg'
            ])->orderBy('id', 'desc')->first(); // retorna una collecion

            if (array_key_exists('subcategories', $category)) {
                foreach ($category['subcategories'] as $subcategory) {
                    Subcategory::create([
                        'name'  => $subcategory['name'],
                        'slug'  => $subcategory['slug'],
                        // 'color' => @$subcategory['color'] ?: false,
                        'category_id' => $new_category->id
                    ]);
                }
            }
        }

        foreach ($brands as $brand) {
            $new_brand = Brand::create([
                'name' => $brand['name'],
                'slug' => $brand['slug'],
            ])->orderBy('id', 'desc')->first();

            $new_brand->categories()->attach(2);
        }

        // $new_brands = Brand::all();
        // foreach ($new_brands as  $brand) {
        //     $brand->categories()->attach(2);
        // }

        foreach ($ages as $age) {
            Age::create([
                'name' => $age['name'],
                'slug' => $age['slug'],
            ]);
        }

        // $new_ages = Age::all();
        // foreach ($new_ages as $age) {
        //     $age->categories()->attach(3);
        // }
    }
}
