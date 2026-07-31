<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Procesador',
                'icon'        => 'bi-cpu',
                'is_optional' => false,
            ],
            [
                'name'        => 'Tarjeta Madre',
                'icon'        => 'bi-motherboard',
                'is_optional' => false,
            ],
            [
                'name'        => 'Memoria RAM',
                'icon'        => 'bi-memory',
                'is_optional' => false,
            ],
            [
                'name'        => 'Almacenamiento Principal',
                'icon'        => 'bi-hdd-rack',
                'is_optional' => false,
            ],
            [
                'name'        => 'Gabinete',
                'icon'        => 'bi-pc-display',
                'is_optional' => false,
            ],
            [
                'name'        => 'Monitores',
                'icon'        => 'bi-display',
                'is_optional' => true,
            ],
            [
                'name'        => 'Kits de Teclado y Mouse',
                'icon'        => 'bi-keyboard',
                'is_optional' => true,
            ],
            [
                'name'        => 'Punto de Venta / POS',
                'icon'        => 'bi-receipt',
                'is_optional' => true,
            ],
            [
                'name'        => 'Enfriamiento Liquido / Disipadores',
                'icon'        => 'bi-fan',
                'is_optional' => false,
            ],
            [
                'name'        => 'Licenciamiento de Software',
                'icon'        => 'bi-app-indicator',
                'is_optional' => true,
            ],
            [
                'name'        => 'Servicios Profesionales',
                'icon'        => 'bi-tools',
                'is_optional' => true,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['name' => $cat['name']],
                [
                    'icon'        => $cat['icon'],
                    'is_optional' => $cat['is_optional'],
                ]
            );
        }
    }
}