<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        
        $vendors = User::where('is_vendor', true)->pluck('id')->toArray();
        
        // If no vendors, just use all users
        if (empty($vendors)) {
            $vendors = User::pluck('id')->toArray();
        }

        if (empty($vendors)) {
            $this->command->warn('No users found to assign services to. Please run UserSeeder first.');
            return;
        }

        $services = [
            ['name' => 'Web Development', 'cat' => 'Digital Services'],
            ['name' => 'Mobile App Development', 'cat' => 'Digital Services'],
            ['name' => 'UI/UX Design', 'cat' => 'Creative Arts'],
            ['name' => 'Logo Design', 'cat' => 'Creative Arts'],
            ['name' => 'SEO Optimization', 'cat' => 'Digital Services'],
            ['name' => 'Social Media Management', 'cat' => 'Digital Services'],
            ['name' => 'Content Writing', 'cat' => 'Creative Arts'],
            ['name' => 'Translation Services', 'cat' => 'Professional Services'],
            ['name' => 'Video Editing', 'cat' => 'Creative Arts'],
            ['name' => 'Graphic Design', 'cat' => 'Creative Arts'],
            ['name' => 'Marketing Strategy', 'cat' => 'Professional Services'],
            ['name' => 'Data Analysis', 'cat' => 'Professional Services'],
            ['name' => 'Cybersecurity Audit', 'cat' => 'Digital Services'],
            ['name' => 'Resume/CV Writing', 'cat' => 'Professional Services'],
            ['name' => 'Tutoring (Math, Programming, etc.)', 'cat' => 'Education & Tutoring'],
            ['name' => 'Photography', 'cat' => 'Creative Arts'],
            ['name' => 'Event Planning', 'cat' => 'Professional Services'],
            ['name' => 'Voice Over Services', 'cat' => 'Creative Arts'],
            ['name' => 'Animation Services', 'cat' => 'Creative Arts'],
            ['name' => 'Game Development', 'cat' => 'Digital Services'],
            ['name' => 'IT Support', 'cat' => 'Digital Services'],
            ['name' => 'Cloud Setup', 'cat' => 'Digital Services'],
            ['name' => 'DevOps Consulting', 'cat' => 'Digital Services'],
            ['name' => 'AI Model Assistance', 'cat' => 'Digital Services'],
            ['name' => 'Shopify Store Setup', 'cat' => 'Digital Services'],
            ['name' => 'WordPress Development', 'cat' => 'Digital Services'],
            ['name' => 'Copywriting', 'cat' => 'Creative Arts'],
            ['name' => 'Email Marketing', 'cat' => 'Digital Services'],
            ['name' => 'Virtual Assistant', 'cat' => 'Professional Services'],
            ['name' => 'Customer Support Services', 'cat' => 'Professional Services'],
        ];

        foreach ($services as $svc) {
            $category = Category::where('name', $svc['cat'])->first();
            
            Service::create([
                'user_id' => $faker->randomElement($vendors),
                'category_id' => $category ? $category->id : null,
                'name' => $svc['name'],
                'slug' => Str::slug($svc['name']) . '-' . Str::random(5),
                'description' => $faker->paragraphs(3, true),
                'price' => $faker->randomFloat(2, 20, 500),
                'availability' => true,
                'image' => null, // Will use default placeholder from model
            ]);
        }

        $this->command->info('ServiceSeeder: 30 services created.');
    }
}
