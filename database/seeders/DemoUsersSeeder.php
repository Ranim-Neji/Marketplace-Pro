<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        // 1. ENSURE ROLES EXIST
        $roles = ['admin', 'vendor', 'buyer'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // 2. CREATE ADMIN
        $admin = User::firstOrCreate(
            ['email' => 'admin@marketplace.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'is_active' => true,
                'is_vendor' => true,
            ]
        );
        $admin->syncRoles(['admin']);

        // 3. CREATE VENDORS (10)
        $shopNames = [
            'Tech Store', 'Fashion Hub', 'Beauty Corner', 'Home Essentials', 
            'Fitness Shop', 'Book World', 'Gadget Zone', 'Kids Store', 
            'Luxury Picks', 'Daily Deals'
        ];

        $vendors = [];
        for ($i = 1; $i <= 10; $i++) {
            $vendor = User::firstOrCreate(
                ['email' => "vendor{$i}@marketplace.com"],
                [
                    'name' => "Vendor {$i}",
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                    'is_active' => true,
                    'is_vendor' => true,
                    'shop_name' => $shopNames[$i - 1],
                    'shop_description' => "Welcome to {$shopNames[$i - 1]}, your trusted source for quality products.",
                ]
            );
            $vendor->syncRoles(['vendor']);
            $vendors[] = $vendor;
        }

        // 4. CREATE BUYERS (20)
        for ($i = 1; $i <= 20; $i++) {
            $buyer = User::firstOrCreate(
                ['email' => "buyer{$i}@marketplace.com"],
                [
                    'name' => "Buyer {$i}",
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                    'is_active' => true,
                    'is_vendor' => false,
                ]
            );
            $buyer->syncRoles(['buyer']);
        }

        // 5. REDISTRIBUTE PRODUCTS (Optional but for realism)
        $products = Product::all();
        if ($products->isNotEmpty() && !empty($vendors)) {
            foreach ($products as $product) {
                $randomVendor = $vendors[array_rand($vendors)];
                $product->update(['user_id' => $randomVendor->id]);
            }
        }
    }
}
