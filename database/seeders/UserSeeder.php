<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $role = Role::create(['name' => 'admin']);


        User::create([
            'name' => 'David Bolivar',
            'email' => 'david14847@gmail.com',
            'password' => bcrypt('david123')
        ])->assignRole($role);

        User::create([
            'name' => 'Rosa Bautista',
            'email' => 'atencionalcliente@kuchaskids.pe',
            // TODO: copiar a "jupiterimportaciones@gmail.com"
            'password' => bcrypt('atencionalcliente2022!')
        ])->assignRole($role);
    }
}
