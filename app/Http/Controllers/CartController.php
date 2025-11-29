<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        // $product = Product::findOrFail($request->product_id);
        
        // Get or create session ID for guest users
        $sessionId = session()->getId();
        $userId = Auth::check() ? Auth::id() : null;

        // Check if product already exists in cart
        $existingCart = Cart::where('product_id', $request->product_id)
            ->when($userId, function($query) use ($userId) {
                return $query->where('user_id', $userId);
            }, function($query) use ($sessionId) {
                return $query->where('session_id', $sessionId)->whereNull('user_id');
            })
            ->first();

        if ($existingCart) {
            // If product already in cart, just update quantity to 1 (as per requirement)
            $existingCart->update([
                'quantity' => 1,
                'price' => $product->mrp ?: $product->selling_price
            ]);
        } else {
            // Add new product to cart with quantity 1
            Cart::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $request->product_id,
                'quantity' => 1,
                'price' => $product->mrp ?: $product->selling_price
            ]);
        }

        // Get updated cart count and total
        $cartData = $this->getCartData($userId, $sessionId);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => $cartData['count'],
            'cart_total' => $cartData['total']
        ]);
    }

    public function getCartData($userId = null, $sessionId = null)
    {
        $cartItems = Cart::with('product')
            ->when($userId, function($query) use ($userId) {
                return $query->where('user_id', $userId);
            }, function($query) use ($sessionId) {
                return $query->where('session_id', $sessionId)->whereNull('user_id');
            })
            ->get();

        $count = $cartItems->count(); // Count distinct products
        $total = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        return [
            'count' => $count,
            'total' => $total,
            'items' => $cartItems
        ];
    }

    public function getCartSummary()
    {
        $sessionId = session()->getId();
        $userId = Auth::check() ? Auth::id() : null;

        $cartData = $this->getCartData($userId, $sessionId);

        return response()->json([
            'cart_count' => $cartData['count'],
            'cart_total' => $cartData['total']
        ]);
    }
}