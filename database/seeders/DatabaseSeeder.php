<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
            DemoUsersSeeder::class,
            ProductSeeder::class,
            ServiceSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
