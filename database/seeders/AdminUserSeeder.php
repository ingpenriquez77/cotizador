<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario Administrador (Acceso total)
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('010704'),
                'role'     => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'ing.pedro.enriquez@gmail.com'],
            [
                'name'     => 'Pedro Rafael Enriquez Nevarez',
                'password' => Hash::make('010704'),
                'role'     => 'viewer',
            ]
        );
    }
}
