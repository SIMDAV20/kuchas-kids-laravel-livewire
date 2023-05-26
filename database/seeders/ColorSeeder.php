<?php

namespace Database\Seeders;

use App\Models\Color;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $colors = [
            [
                'name' => 'Rosado',
                'slug' => Str::slug('Rosado'),
                'hex' => '#FF5659',
            ], // 1
            [
                'name' => 'Verde',
                'slug' => Str::slug('Verde'),
                'hex' => '#008F39',
            ], // 2
            [
                'name' => 'Caqui',
                'slug' => Str::slug('Caqui'),
                'hex' => '#e0d8b0',
            ], // 3
            [
                'name' => 'Blanco',
                'slug' => Str::slug('Blanco'),
                'hex' => '#FFFFFF',
            ], // 4
            [
                'name' => 'Melón',
                'slug' => Str::slug('Melón'),
                'hex' => '#FDBCB4',
            ], // 5
            [
                'name' => 'Morado',
                'slug' => Str::slug('Morado'),
                'hex' => '#800080',
            ], // 6
            [
                'name' => 'Azul',
                'slug' => Str::slug('Azul'),
                'hex' => '#0000ff',
            ], // 7
            [
                'name' => 'Lila',
                'slug' => Str::slug('Lila'),
                'hex' => '#c8a2c8',
            ], // 8
            [
                'name' => 'Azul Claro',
                'slug' => Str::slug('Azul Claro'),
                'hex' => '#87cefa',
            ], // 9
            [
                'name' => 'Amarillo',
                'slug' => Str::slug('Amarillo'),
                'hex' => '#ffe135',
            ], // 10
            [
                'name' => 'Verde Agua',
                'slug' => Str::slug('Verde Agua'),
                'hex' => '#87F7E4',
            ], // 11
            [
                'name' => 'Verde Kaki',
                'slug' => Str::slug('Verde Kaki'),
                'hex' => '#D1EF8C',
            ], // 12
            [
                'name' => 'Gris Claro',
                'slug' => Str::slug('Gris Claro'),
                'hex' => '#E6ECF6',
            ], // 13
            [
                'name' => 'Celeste',
                'slug' => Str::slug('Celeste'),
                'hex' => '#0DBBF7',
            ], // 14
        ];

        foreach ($colors as $color) {
            Color::create([
                'name' => $color['name'],
                'slug' => $color['slug'],
                'hex' => $color['hex'],
            ]);
        }
    }
}
