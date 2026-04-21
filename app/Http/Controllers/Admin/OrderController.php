<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderStatusChangedNotification;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product'])->latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $orders = $query->paginate(20)->withQueryString();
        $statuses = ['pending', 'processing', 'validated', 'shipped', 'delivered', 'cancelled', 'completed'];

        return view('pages.admin.orders.index', compact('orders', 'statuses'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'user');
        return view('pages.admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,validated,shipped,delivered,cancelled,completed',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $order->status;
        $order->update($request->only(['status', 'payment_status', 'notes']));

        // Notify user of status change
        if ($oldStatus !== $request->status) {
            $order->user->notify(new OrderStatusChangedNotification($order, $oldStatus));
        }

        return back()->with('success', "Order manifest updated successfully.");
    }
}
