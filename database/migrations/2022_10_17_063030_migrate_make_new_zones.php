<?php

use App\Models\District;
use App\Models\Zone;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateMakeNewZones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = [
            [
                'name' => 'LIMA ZONA 1',
                'cost' => 10,
                'districts' => [
                    'San Miguel',
                    'Pueblo Libre',
                    'Jesús María',
                    'Magdalena del Mar',
                    'Lince',
                    'San Isidro',
                    'Miraflores',
                    'Surquillo',
                    'San Borja',
                    'Barranco',
                    'Santiago de Surco',
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
                    // 'Callao',
                    'Rimac',
                    'Lima',
                    'La Victoria',
                    'Los Olivos',
                    'San Martín de Porres',
                    'Independencia'
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

        // $provinces_lima = District::where('deparment_id', 15)->where('province_id', 1501)->where('')->get();
        foreach ($data as $key => $value) {
            foreach ($value['districts'] as $key => $district) {
                $dist = District::where(
                    [
                        ['department_id', '=', 15],
                        ['province_id', '=', 1501],
                        ['name', '=', $district],
                    ]
                )->first();

                if ($dist) {
                    $dist->zone_id = Zone::where('name', $value['name'])->first()->id;
                    $dist->save();
                } else {
                    dd('no existo', $district);
                }
            }
        }


        $zone = Zone::create([
            'name' => 'CALLAO ZONA 4',
            'cost' => 12
        ]);

        $districts = District::where([['department_id', '=', '07'], ['province_id', '=', '0701']])->get()->pluck('id');
        District::whereIn('id', $districts)->update(['zone_id' => 4]);
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
}
