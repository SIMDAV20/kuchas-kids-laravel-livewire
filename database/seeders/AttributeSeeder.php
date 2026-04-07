<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Crear Atributo Color
        $colorId = DB::table('attributes')->insertGetId([
            'name' => 'Color',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Opciones de Color
        $colors = [
            ['value' => 'Blanco', 'hex' => '#FFFFFF'],
            ['value' => 'Negro', 'hex' => '#000000'],
            ['value' => 'Rojo', 'hex' => '#FF0000'],
            ['value' => 'Azul', 'hex' => '#0000FF'],
            ['value' => 'Verde', 'hex' => '#008000'],
            ['value' => 'Amarillo', 'hex' => '#FFFF00'],
            ['value' => 'Rosado', 'hex' => '#FFC0CB'],
            ['value' => 'Celeste', 'hex' => '#87CEEB'],
            ['value' => 'Gris', 'hex' => '#808080'],
            ['value' => 'Melange', 'hex' => '#E0E0E0'],
        ];

        foreach ($colors as $color) {
            DB::table('attribute_options')->insert([
                'attribute_id' => $colorId,
                'value' => $color['value'],
                'hex' => $color['hex'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Crear Atributo Talla
        $tallaId = DB::table('attributes')->insertGetId([
            'name' => 'Talla',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Opciones de Talla (Tallas infantiles comunes)
        $tallas = ['2', '4', '6', '8', '10', '12', '14', '16'];

        foreach ($tallas as $talla) {
            DB::table('attribute_options')->insert([
                'attribute_id' => $tallaId,
                'value' => $talla,
                'hex' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
