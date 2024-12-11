<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Comedero;
use App\Models\Mascota;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'user1',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('12345678'),
            'estado' => 1,
            'rol' => 'user',
        ]);

        
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'estado' => 1,
            'rol' => 'admin',
        ]);

        Mascota::create([
            'usuario_id' => 1,
            'nombre' => "chuy",
            'animal' => 'gato'
        ]);

        Comedero::create([
            'usuario_id' => 1,
            'mascota_id' => 1
        ]);
    }
}
