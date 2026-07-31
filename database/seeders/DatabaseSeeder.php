<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Client;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Usuarios Administradores
        if (User::count() < 2) {
            $this->call([
                AdminUserSeeder::class,
            ]);
        }

        // 2. Clientes de prueba
        if (Client::count() === 0) {
            $this->call([
                ClientSeeder::class,
            ]);
        }

        // 3. Categorías (deben existir antes que los productos)
        if (Category::count() === 0) {
            $this->call([
                CategorySeeder::class,
            ]);
        }

        // 4. Productos
        if (Product::count() === 0) {
            $this->call([
                ProductSeeder::class,
            ]);
        }
    }
}