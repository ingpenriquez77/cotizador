<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::updateOrCreate(
            ['business_name' => 'Modelorama Np-Mod Emiliano Zapata'],
            [
                'contact_name' => 'Juana SArabia',
                'phone'        => '6677976114',
                'email'        => 'pedroenriquez81@gmail.cm',
                'address'      => 'Blvd. Emiliano Zapata, Culiacán, Sin.',
                'status'       => 'activo',
            ]
        );
    }
}
