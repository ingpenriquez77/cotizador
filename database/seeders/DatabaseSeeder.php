<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Client;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (User::count() < 2) {
            $this->call([
                AdminUserSeeder::class,
            ]);
        }

        if (Client::count() === 0) {
            $this->call([
                ClientSeeder::class,
            ]);
        }

        if (Product::count() === 0) {
            $this->call([
                ProductSeeder::class,
            ]);
        }
    }
}
