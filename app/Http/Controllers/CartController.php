<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCartItemRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\UserBehavior;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'active']);
    }

    private function getOrCreateCart(): Cart
    {
        return Cart::firstOrCreate(['user_id' => Auth::id()]);
    }

    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load('items.product.categories');

        return view('pages.cart.index', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate(['quantity' => 'nullable|integer|min:1|max:100']);

        if (!$product->isInStock()) {
            return back()->with('error', 'Product is out of stock.');
        }

        $cart = $this->getOrCreateCart();
        $quantity = $request->input('quantity', 1);

        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;

            if ($newQuantity > $product->stock) {
                return back()->with('error', 'Requested quantity exceeds available stock.');
            }

            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            if ($quantity > $product->stock) {
                return back()->with('error', 'Requested quantity exceeds available stock.');
            }

            $cart->items()->create([
                'product_id' => $product->id,
                'quantity'   => $quantity,
                'price'      => $product->effective_price,
            ]);
        }

        // Track cart behavior for recommendations
        UserBehavior::create([
            'user_id'    => Auth::id(),
            'product_id' => $product->id,
            'action'     => 'cart',
            'score'      => UserBehavior::SCORES['cart'],
        ]);

        if ($request->ajax()) {
            $cart->refresh();
            return response()->json([
                'success'    => true,
                'message'    => 'Product added to cart!',
                'cart_count' => $cart->item_count,
            ]);
        }

        return back()->with('success', 'Product added to cart!')->with('open_cart', true);
    }

    public function update(UpdateCartItemRequest $request, CartItem $item)
    {
        abort_if($item->cart->user_id !== Auth::id(), 403);

        if ($request->integer('quantity') > $item->product->stock) {
            return back()->with('error', 'Requested quantity exceeds available stock.')->with('open_cart', true);
        }

        $item->update(['quantity' => $request->integer('quantity')]);

        return back()->with('success', 'Cart updated.')->with('open_cart', true);
    }

    public function remove(CartItem $item)
    {
        abort_if($item->cart->user_id !== Auth::id(), 403);

        $item->delete();

        return back()->with('success', 'Item removed from cart.')->with('open_cart', true);
    }

    public function clear()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        $cart?->items()->delete();

        return back()->with('success', 'Cart cleared.');
    }
}
