<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserRoleSeeder;
use Database\Seeders\StockSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            UserRoleSeeder::class,
            StockSeeder::class, // Ajout du seeder pour remplir le stock
        ]);
    }
}
