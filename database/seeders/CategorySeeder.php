<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'description' => 'Laptops, Smartphones, and more.'],
            ['name' => 'Fashion', 'description' => 'Latest trends in clothing and accessories.'],
            ['name' => 'Home & Garden', 'description' => 'Furniture, decor, and tools.'],
            ['name' => 'Beauty & Health', 'description' => 'Cosmetics and personal care.'],
            ['name' => 'Sports & Outdoors', 'description' => 'Gear for your next adventure.'],
            ['name' => 'Digital Services', 'description' => 'Web dev, SEO, and IT solutions.'],
            ['name' => 'Creative Arts', 'description' => 'Design, Photography, and Video.'],
            ['name' => 'Professional Services', 'description' => 'Legal, Accounting, and Consulting.'],
            ['name' => 'Education & Tutoring', 'description' => 'Learn new skills with experts.'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}
