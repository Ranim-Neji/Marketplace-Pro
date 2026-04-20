<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = User::query()
            ->where('is_vendor', true)
            ->get();

        $categories = Category::query()->pluck('id')->all();

        if ($vendors->isEmpty() || empty($categories)) {
            return;
        }

        Product::unguard();

        // ─── 10 Premium Bestsellers ──────────────────────────────────────────
        $bestsellers = [
            [
                'title' => 'Nova Wireless Headphones',
                'description' => 'Immerse yourself in pure audio bliss with the Nova Wireless Headphones. Featuring advanced active noise cancellation and 40 hours of battery life.',
                'price' => 299.99,
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=800&auto=format&fit=crop',
                'rating' => 4.9,
            ],
            [
                'title' => 'Aura Smartwatch Pro',
                'description' => 'Track your health, fitness, and notifications with the elegant Aura Smartwatch Pro. Features a stunning OLED display and titanium casing.',
                'price' => 349.00,
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=800&auto=format&fit=crop',
                'rating' => 4.8,
            ],
            [
                'title' => 'Velvet Matte Lip Kit',
                'description' => 'A luxurious collection of velvet matte lipsticks. Long-lasting, highly pigmented, and enriched with hydrating oils for all-day comfort.',
                'price' => 85.00,
                'image' => 'https://images.unsplash.com/photo-1586495777744-4413f21062fa?q=80&w=800&auto=format&fit=crop',
                'rating' => 4.9,
            ],
            [
                'title' => 'Minimalist Desk Lamp',
                'description' => 'Brighten your workspace with this sleek, adjustable minimalist desk lamp. Features warm and cool light settings with smart touch controls.',
                'price' => 120.00,
                'image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?q=80&w=800&auto=format&fit=crop',
                'rating' => 4.7,
            ],
            [
                'title' => 'Urban Leather Backpack',
                'description' => 'Crafted from premium full-grain leather, the Urban Backpack is designed for the modern professional. Spacious, durable, and sophisticated.',
                'price' => 245.00,
                'image' => 'https://images.unsplash.com/photo-1491637639811-60e2756cc1c7?q=80&w=800&auto=format&fit=crop',
                'rating' => 4.6,
            ],
            [
                'title' => 'AeroFit Running Sneakers',
                'description' => 'Experience cloud-like comfort and dynamic support with AeroFit sneakers. Designed for peak performance and everyday wear.',
                'price' => 175.00,
                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=800&auto=format&fit=crop',
                'rating' => 4.8,
            ],
            [
                'title' => 'Glow Skincare Serum',
                'description' => 'Rejuvenate your skin with our signature Glow Serum. Packed with Vitamin C and hyaluronic acid for a radiant, youthful complexion.',
                'price' => 95.00,
                'image' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?q=80&w=800&auto=format&fit=crop',
                'rating' => 4.9,
            ],
            [
                'title' => 'Pro Mechanical Keyboard',
                'description' => 'Elevate your typing experience with tactile mechanical switches, customizable RGB lighting, and a solid aluminum frame.',
                'price' => 160.00,
                'image' => 'https://images.unsplash.com/photo-1595225476474-87563907a212?q=80&w=800&auto=format&fit=crop',
                'rating' => 4.7,
            ],
            [
                'title' => 'Crystal Perfume Essence',
                'description' => 'An enchanting fragrance blending floral notes with deep amber undertones. Housed in a beautifully crafted crystal bottle.',
                'price' => 210.00,
                'image' => 'https://images.unsplash.com/photo-1594035910387-fea47794261f?q=80&w=800&auto=format&fit=crop',
                'rating' => 4.8,
            ],
            [
                'title' => 'Magnetic Phone Stand',
                'description' => 'A heavy-duty aluminum magnetic stand for your smartphone. Features 360-degree rotation and a sleek, minimalist aesthetic.',
                'price' => 65.00,
                'image' => 'https://images.unsplash.com/photo-1585298723682-7115561c51b7?q=80&w=800&auto=format&fit=crop',
                'rating' => 4.6,
            ],
        ];

        foreach ($bestsellers as $index => $item) {
            $vendor = $vendors[$index % $vendors->count()];

            $product = Product::updateOrCreate(
                ['slug' => Str::slug($item['title'])],
                [
                    'user_id' => $vendor->id,
                    'title' => $item['title'],
                    'description' => $item['description'],
                    'short_description' => Str::limit($item['description'], 80),
                    'price' => $item['price'],
                    'sale_price' => rand(0, 1) ? $item['price'] * 0.9 : null,
                    'stock' => rand(20, 100),
                    'status' => 'active',
                    'is_featured' => true,
                    'is_bestseller' => true,
                    'average_rating' => $item['rating'],
                    'views_count' => rand(1000, 5000),
                    'image' => $item['image'],
                    'sku' => 'BST-'.strtoupper(Str::random(6)),
                ]
            );

            $product->categories()->sync(collect($categories)->shuffle()->take(rand(1, 2))->all());
        }

        // ─── 40 Normal Products ──────────────────────────────────────────────
        $adjectives = ['Classic', 'Modern', 'Vintage', 'Eco-friendly', 'Premium', 'Essential', 'Smart', 'Luxury', 'Compact', 'Pro'];
        $nouns = ['Watch', 'Camera', 'Speaker', 'Jacket', 'Sunglasses', 'Wallet', 'Desk Mat', 'Mug', 'Planter', 'Notebook', 'T-Shirt', 'Water Bottle', 'Mouse', 'Tablet Case', 'Candle'];
        
        // Random generic high quality unsplash links for normal products
        $images = [
            'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1503602642458-232111445657?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1546868871-7041f2a55e12?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1524805444758-089113d48a6d?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1512496015851-a1cbffb67cb1?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1572635196237-14b3f281501f?q=80&w=800&auto=format&fit=crop',
        ];

        for ($i = 1; $i <= 40; $i++) {
            $vendor = $vendors->random();
            $title = $adjectives[array_rand($adjectives)] . ' ' . $nouns[array_rand($nouns)] . ' ' . rand(1, 99);
            $price = rand(10, 300) + 0.99;

            $product = Product::updateOrCreate(
                ['slug' => Str::slug($title) . '-' . $i],
                [
                    'user_id' => $vendor->id,
                    'title' => $title,
                    'description' => 'Discover the perfect blend of style and utility with our ' . $title . '. Ideal for everyday use and built to last.',
                    'short_description' => 'A versatile and reliable ' . strtolower($title) . ' for everyday use.',
                    'price' => $price,
                    'sale_price' => rand(0, 10) > 7 ? $price * 0.8 : null,
                    'stock' => rand(5, 50),
                    'status' => 'active',
                    'is_featured' => false,
                    'is_bestseller' => false,
                    'average_rating' => rand(35, 45) / 10, // 3.5 to 4.5
                    'views_count' => rand(50, 500),
                    'image' => $images[array_rand($images)],
                    'sku' => 'NRM-'.strtoupper(Str::random(6)),
                ]
            );

            $product->categories()->sync(collect($categories)->shuffle()->take(rand(1, 2))->all());
        }

        Product::reguard();
    }
}
