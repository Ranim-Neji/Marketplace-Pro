<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\UserBehavior;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->where('is_active', true)->get();
        $products = Product::query()->active()->get();

        if ($users->count() < 2 || $products->isEmpty()) {
            return;
        }

        foreach ($users->take(8) as $user) {
            $picked = $products->random(min(rand(1, 3), $products->count()));
            $picked = $picked instanceof Product ? collect([$picked]) : $picked;

            $subtotal = 0;
            $itemsPayload = [];

            foreach ($picked as $product) {
                $quantity = rand(1, 2);
                $price = (float) $product->effective_price;
                $lineSubtotal = round($price * $quantity, 2);

                $itemsPayload[] = [
                    'product_id' => $product->id,
                    'product_title' => $product->title,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $lineSubtotal,
                ];

                $subtotal += $lineSubtotal;
            }

            $tax = round($subtotal * 0.075, 2);
            $shipping = $subtotal >= 100 ? 0 : 9.99;
            $total = round($subtotal + $tax + $shipping, 2);
            $status = Arr::random(['pending', 'processing', 'validated', 'shipped', 'delivered']);

            $order = Order::create([
                'order_number' => 'ORD-SEED-'.strtoupper(Str::random(8)),
                'user_id' => $user->id,
                'status' => $status,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'payment_method' => Arr::random(['cash_on_delivery', 'credit_card']),
                'payment_status' => Arr::random(['pending', 'paid']),
                'shipping_address' => $user->address ?: '123 Demo Street, Lagos',
            ]);

            foreach ($itemsPayload as $payload) {
                $order->items()->create($payload);

                UserBehavior::create([
                    'user_id' => $user->id,
                    'product_id' => $payload['product_id'],
                    'action' => 'purchase',
                    'score' => UserBehavior::SCORES['purchase'],
                ]);

                if ($status === 'delivered') {
                    Review::updateOrCreate(
                        [
                            'product_id' => $payload['product_id'],
                            'user_id' => $user->id,
                        ],
                        [
                            'rating' => rand(3, 5),
                            'title' => 'Great product',
                            'comment' => 'Very satisfied with quality and delivery.',
                            'is_approved' => true,
                        ]
                    );
                }
            }
        }

        foreach ($users->take(10) as $user) {
            $wishlistProducts = $products->random(min(rand(1, 3), $products->count()));
            $wishlistProducts = $wishlistProducts instanceof Product ? collect([$wishlistProducts]) : $wishlistProducts;

            foreach ($wishlistProducts as $product) {
                Wishlist::firstOrCreate([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                ]);

                UserBehavior::firstOrCreate([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'action' => 'wishlist',
                ], [
                    'score' => UserBehavior::SCORES['wishlist'],
                ]);
            }
        }

        $participants = $users->take(2)->values();
        $pair = [$participants[0]->id, $participants[1]->id];
        sort($pair);

        $conversation = Conversation::firstOrCreate([
            'user_one_id' => $pair[0],
            'user_two_id' => $pair[1],
        ], [
            'last_message_at' => now(),
        ]);

        if (! $conversation->messages()->exists()) {
            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $pair[0],
                'body' => 'Hello, is this item still available?',
            ]);

            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $pair[1],
                'body' => 'Yes, it is available right now.',
            ]);

            $conversation->update(['last_message_at' => now()]);
        }
    }
}
