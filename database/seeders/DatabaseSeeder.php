<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // Storage::deleteDirectory('categories');
        // Storage::makeDirectory('categories');

        // Storage::deleteDirectory('subcategories');
        // Storage::makeDirectory('subcategories');

        // Storage::deleteDirectory('products');
        // Storage::makeDirectory('products');

        // $this->call(UserSeeder::class);
        // $this->call(CategorySeeder::class);
        // $this->call(ColorSeeder::class);
        // $this->call(SizeSeeder::class);
        // $this->call(ProductSeeder::class);
        // $this->call(DepartmentSeeder::class);
        // $this->call(CitySeeder::class);
        // $this->call(DistrictSeeder::class);

        // Setting::create([
        //     'min_amount' => 200
        // ]);
        // $this->call(SubcategorySeeder::class);


        // $this->call(ColorProductSeeder::class);


        // $this->call(ColorSizeSeeder::class);

        // $this->call(DepartmentSeeder::class);
        $this->call(AttributeSeeder::class);
    }
}
