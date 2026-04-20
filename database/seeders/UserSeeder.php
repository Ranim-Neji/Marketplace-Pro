<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = [
            ['name' => 'Aisha Seller', 'email' => 'aisha@marketplace.com', 'shop_name' => 'Aisha Tech Hub'],
            ['name' => 'Emeka Vendor', 'email' => 'emeka@marketplace.com', 'shop_name' => 'Emeka Gadgets'],
            ['name' => 'Zara Store', 'email' => 'zara@marketplace.com', 'shop_name' => 'Zara Fashion'],
        ];

        foreach ($vendors as $vendor) {
            $user = User::updateOrCreate(
                ['email' => $vendor['email']],
                [
                    'name' => $vendor['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'is_active' => true,
                    'is_vendor' => true,
                    'shop_name' => $vendor['shop_name'],
                    'shop_description' => 'Trusted marketplace seller',
                ]
            );

            $user->syncRoles(['vendor']);
        }

        User::factory(12)->create([
            'is_active' => true,
            'is_vendor' => true,
            'email_verified_at' => now(),
        ])->each(function (User $user): void {
            $user->syncRoles(['user']);
        });
    }
}
