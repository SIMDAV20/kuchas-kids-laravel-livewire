<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Province::create([
            'department_id' => 1,
            'name' => 'Lima'
        ]);
    }
}
