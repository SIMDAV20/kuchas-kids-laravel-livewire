<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Zone;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'LIMA ZONA 1',
                'cost' => 10,
                'districts' => [
                    'San Miguel',
                    'Pueblo Libre',
                    'Jesús María',
                    'Magdalena',
                    'Lince',
                    'San Isidro',
                    'Miraflores',
                    'Surquillo',
                    'San Borja',
                    'Barranco',
                    'Surco',
                    'La Molina',
                    'Chorrillos',
                    'Santa Anita',
                    'Breña',
                    'San Luís'
                ]
            ],
            [
                'name' => 'LIMA ZONA 2',
                'cost' => 12,
                'districts' => [
                    'Ate',
                    'Villa María del Triunfo',
                    'San Juan de Miraflores',
                    'Villa el Salvador',
                    'San Juan de Lurigancho',
                    'El Agustino',
                    'Callao',
                    'Rimac',
                    'Lima Cercado',
                    'La Victoria',
                    'Los Olivos',
                    'San Martín de Porres',
                    'Indepeniencia'
                ]
            ],
            [
                'name' => 'LIMA ZONA 3',
                'cost' => 12,
                'districts' => [
                    'Carabayllo',
                    'Puente Piedra',
                    'Comas'
                ]
            ],
        ];

        foreach ($data as $key => $zone) {
            $new_zone = Zone::create([
                'name' => $zone['name'],
                'cost' => $zone['cost']
            ])->orderBy('id', 'desc')->first();

            foreach ($zone['districts'] as $key => $district) {
                $new_district = District::create([
                    'name' => $district,
                    'province_id' => 1,
                    'zone_id' => $new_zone->id
                ]);
            }
        }
    }
}
