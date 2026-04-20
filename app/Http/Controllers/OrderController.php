<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\UserBehavior;
use App\Notifications\OrderPlacedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'active']);
    }

    public function index()
    {
        $orders = Auth::user()
            ->orders()
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('pages.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load('items.product', 'user');

        return view('pages.orders.show', compact('order'));
    }

    public function checkout()
    {
        $cart = Cart::query()
            ->where('user_id', Auth::id())
            ->with('items.product')
            ->first();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        return view('pages.orders.checkout', compact('cart'));
    }

    public function store(StoreOrderRequest $request)
    {
        $cart = Cart::query()
            ->where('user_id', Auth::id())
            ->with('items.product')
            ->first();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $order = DB::transaction(function () use ($request, $cart): Order {
            foreach ($cart->items as $item) {
                if ($item->quantity > $item->product->stock) {
                    abort(422, "Insufficient stock for {$item->product->title}.");
                }
            }

            $subtotal = $cart->total;
            $tax = round($subtotal * 0.075, 2);
            $shipping = $subtotal >= 100 ? 0 : 9.99;
            $total = round($subtotal + $tax + $shipping, 2);

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'status' => Order::STATUS_PENDING,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'payment_method' => $request->string('payment_method')->value(),
                'shipping_address' => $request->string('shipping_address')->value(),
                'notes' => $request->string('notes')->value() ?: null,
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_title' => $item->product->title,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->subtotal,
                ]);

                $item->product->decrement('stock', $item->quantity);

                UserBehavior::create([
                    'user_id' => Auth::id(),
                    'product_id' => $item->product_id,
                    'action' => 'purchase',
                    'score' => UserBehavior::SCORES['purchase'],
                ]);
            }

            $cart->items()->delete();

            return $order;
        });

        Auth::user()->notify(new OrderPlacedNotification($order));

        return redirect()
            ->route('orders.show', $order)
            ->with('success', "Order #{$order->order_number} placed successfully.");
    }

    public function cancel(Order $order)
    {
        $this->authorize('update', $order);

        if (! in_array($order->status, [Order::STATUS_PENDING, Order::STATUS_PROCESSING], true)) {
            return back()->with('error', 'This order can no longer be cancelled.');
        }

        DB::transaction(function () use ($order): void {
            $order->update(['status' => Order::STATUS_CANCELLED]);

            $order->loadMissing('items.product');
            foreach ($order->items as $item) {
                $item->product?->increment('stock', $item->quantity);
            }
        });

        return back()->with('success', 'Order cancelled successfully.');
    }
}
