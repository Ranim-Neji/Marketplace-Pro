<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\UserBehavior;
use App\Notifications\OrderPlacedNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $orders = $request->user()
            ->orders()
            ->with('items.product')
            ->latest()
            ->paginate((int) $request->input('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $orders->items(),
            'meta' => [
                'total' => $orders->total(),
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
            ],
        ]);
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $payload = $request->validated();

        if (empty($payload['items'])) {
            return response()->json([
                'success' => false,
                'message' => 'API order creation requires an items payload.',
            ], 422);
        }

        $order = DB::transaction(function () use ($request, $payload): Order {
            $itemPayload = collect($payload['items'])
                ->groupBy('product_id')
                ->map(fn($group) => (int) $group->sum('quantity'));

            $products = Product::query()
                ->whereIn('id', $itemPayload->keys())
                ->active()
                ->get()
                ->keyBy('id');

            if ($products->count() !== $itemPayload->count()) {
                abort(422, 'One or more selected products are unavailable.');
            }

            foreach ($itemPayload as $productId => $quantity) {
                if ($quantity > $products[$productId]->stock) {
                    abort(422, "Insufficient stock for {$products[$productId]->title}.");
                }
            }

            $subtotal = (float) $itemPayload->sum(fn($quantity, $productId) => $products[$productId]->effective_price * $quantity);
            $tax = round($subtotal * 0.075, 2);
            $shipping = $subtotal >= 100 ? 0 : 9.99;
            $total = round($subtotal + $tax + $shipping, 2);

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $request->user()->id,
                'status' => Order::STATUS_PENDING,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'payment_method' => $payload['payment_method'],
                'shipping_address' => $payload['shipping_address'],
                'notes' => $payload['notes'] ?? null,
            ]);

            foreach ($itemPayload as $productId => $quantity) {
                $product = $products[$productId];
                $price = (float) $product->effective_price;

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_title' => $product->title,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => round($price * $quantity, 2),
                ]);

                $product->decrement('stock', $quantity);

                UserBehavior::create([
                    'user_id' => $request->user()->id,
                    'product_id' => $product->id,
                    'action' => 'purchase',
                    'score' => UserBehavior::SCORES['purchase'],
                ]);
            }

            return $order;
        });

        $request->user()->notify(new OrderPlacedNotification($order));

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully.',
            'data' => $order->load('items.product'),
        ], 201);
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        abort_unless($order->user_id === $request->user()->id || $request->user()->isAdmin(), 403);

        return response()->json([
            'success' => true,
            'data' => $order->load('items.product'),
        ]);
    }

    public function update(Request $request, Order $order): JsonResponse
    {
        abort_unless($order->user_id === $request->user()->id || $request->user()->isAdmin(), 403);

        $request->validate([
            'status' => ['required', 'in:cancelled'],
        ]);

        if (! in_array($order->status, [Order::STATUS_PENDING, Order::STATUS_PROCESSING], true)) {
            return response()->json([
                'success' => false,
                'message' => 'This order can no longer be cancelled.',
            ], 422);
        }

        DB::transaction(function () use ($order): void {
            $order->update(['status' => Order::STATUS_CANCELLED]);

            $order->loadMissing('items.product');
            foreach ($order->items as $item) {
                $item->product?->increment('stock', $item->quantity);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully.',
            'data' => $order->fresh()->load('items.product'),
        ]);
    }

    public function destroy(Request $request, Order $order): JsonResponse
    {
        return $this->update($request->merge(['status' => 'cancelled']), $order);
    }
}
