<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function indexPage() {
        // return 'nihi';
        $user = null;
        $profile = null;
        $dashboardPage = null;
        $imgPath = 'img/default_profile.webp';
        
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->type == 'vendor') {
                $profile = $user->vendorProfile;
                $imgPath = $profile && $profile->profile_picture 
                ? "vendor/profile/{$profile->profile_picture}" 
                : "img/default_profile.webp";
                $dashboardPage = route('vendor.dashboard');
            } else {
                $profile = $user->customerProfile;
                $imgPath = $profile && $profile->profile_image 
                ? "customer/profile/{$profile->profile_image}" 
                : "img/default_profile.webp";
                // dd($profile->profile_image);  
                $dashboardPage = route('customer.dashboard');
            }
        }
        return view('cart', compact(
            'user', 'profile', 'dashboardPage', 'imgPath'
        ));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required',
        ]);
        
        
        $product = Product::findOrFail($request->product_id);
        
        // Get or create session ID for guest users
        $sessionId = session()->getId();
        $userId = Auth::check() ? Auth::id() : null;
        
        // Check if product already exists in cart
        // $existingCart = Cart::where('product_id', $request->product_id)
        //     ->when($userId, function($query) use ($userId) {
        //         return $query->where('user_id', $userId);
        //     }, function($query) use ($sessionId) {
        //         return $query->where('session_id', $sessionId)->whereNull('user_id');
        //     })
        //     ->first();

        // if ($existingCart) {
        //     // If product already in cart, just update quantity to 1 (as per requirement)
        //     $existingCart->update([
        //         'quantity' => 1,
        //         'price' => $product->mrp ?: $product->selling_price
        //     ]);
        // } else {
            // Add new product to cart with quantity 1
            Cart::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $product->selling_price*1.17
            ]);
        // }
            
        // Get updated cart count and total
        $cartData = $this->getCartData($userId, $sessionId);
        
        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => $cartData['count'],
            'cart_total' => $cartData['total']
        ]);
        // return response()->json([ 'success' => $cartData ]);
    }

    public function buy($product_id, $quantity)
    {  
        $product = Product::findOrFail($product_id);
        
        // Get or create session ID for guest users
        $sessionId = session()->getId();
        $userId = Auth::check() ? Auth::id() : null;

        Cart::where('single_buy', true)->whereNull('order_id')->delete();

        $temp = Cart::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price' => $product->mrp ?: $product->selling_price,
            'single_buy' => true
        ]);
        // dd($temp);

        
            
        // Get updated cart count and total
        // $cartData = $this->getCartData($userId, $sessionId);
        
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Product added to cart successfully!',
        //     'cart_count' => $cartData['count'],
        //     'cart_total' => $cartData['total']
        // ]);
        

        $user = null;
        $profile = null;
        $dashboardPage = null;
        $imgPath = 'img/default_profile.webp';
        
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->type == 'vendor') {
                $profile = $user->vendorProfile;
                $imgPath = $profile && $profile->profile_picture 
                ? "vendor/profile/{$profile->profile_picture}" 
                : "img/default_profile.webp";
                $dashboardPage = route('vendor.dashboard');
            } else {
                $profile = $user->customerProfile;
                $imgPath = $profile && $profile->profile_image 
                ? "customer/profile/{$profile->profile_image}" 
                : "img/default_profile.webp";
                // dd($profile->profile_image);  
                $dashboardPage = route('customer.dashboard');
            }
        }
        $userId = $user->user_id ?? null;
        $sessionId = session()->getId();
        // dd($user);

        // Get user addresses
        $addresses = DB::table('customer_addresses')
            ->where('user_id', $userId)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

            // dd($userId);
        // Get cart items with product details
        $cartItems = DB::table('carts as c')
            ->join('vendor_products as vp', 'c.product_id', '=', 'vp.id')
            ->leftJoin('vendor_product_images as vpi', function($join) {
                $join->on('vp.id', '=', 'vpi.product_id')
                    ->where('vpi.is_primary', 1);
            })
            ->where(function($query) use ($userId, $sessionId) {
                $query->where('c.user_id', $userId)
                    ->orWhere('c.session_id', $sessionId);
            })
            ->whereNull('order_id')
            ->where('single_buy', true)
            ->orderBy('c.created_at', 'desc')
            ->select(
                'c.id as cart_id',
                'c.product_id',
                'c.quantity as cq',
                'c.price',
                'c.created_at as cart_created_at',
                'vp.*',
                'vpi.image_path as primary_image'
            )
            ->get();
            // dd($cartItems);

        // Calculate totals
        $subtotal = $cartItems->sum(function($item) {
            return $item->quantity * $item->selling_price;
        });

        $shipping = 2.50; // Fixed shipping for now
        $tax = $subtotal * 0.05; // 5% tax
        $discount = 15.00; // Fixed discount for now
        $total = $subtotal + $shipping + $tax - $discount;

        if(!$user) {
            return "please <a href='https://arslan.mjcheezain.com/login-user?type=customer-login&page=product/40/buy/1'> Register or login </a> your account";
        }
        return view('checkout', compact(
            'user', 'profile', 'dashboardPage', 'imgPath',
            'addresses',
            'cartItems',
            'subtotal',
            'shipping',
            'tax',
            'discount',
            'total'
        ));
    }

    public function getCartData($userId = null, $sessionId = null)
    {
        $cartItems = Cart::with('product')
            ->when($userId, function($query) use ($userId) {
                return $query->where('user_id', $userId);
            }, function($query) use ($sessionId) {
                return $query->where('session_id', $sessionId)->whereNull('user_id');
            })
            ->whereNull('order_id')
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



    public function getCartItems()
    {
        $sessionId = session()->getId();
        $userId = Auth::check() ? Auth::id() : null;

        $cartItems = DB::table('carts')
        ->leftJoin('vendor_products', 'carts.product_id', '=', 'vendor_products.id')
        ->leftJoin('vendor_product_images', function($join) {
            $join->on('vendor_products.id', '=', 'vendor_product_images.product_id')
                ->where('vendor_product_images.is_primary', '=', 1);
        })
        ->when($userId, function($query) use ($userId) {
            return $query->where('carts.user_id', $userId);
        }, function($query) use ($sessionId) {
            return $query->where('carts.session_id', $sessionId)
                        ->whereNull('carts.user_id');
        })
        ->select(
            'carts.*',
            'vendor_products.name as product_name',
            'vendor_products.selling_price',
            'vendor_products.mrp',
            'vendor_product_images.image_path'
        )
        ->whereNull('order_id')
        ->get();
        // return response()->json([
        //     'success' => $cartItems
        // ]);

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => true,
                'items' => [],
                'totals' => [
                    'subtotal' => 0,
                    'shipping_fee' => 0,
                    'total' => 0
                ]
            ]);
        }

        $subtotal = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        $shippingFee = 300; // Fixed shipping fee or calculate based on your logic
        $total = $subtotal + $shippingFee;

        return response()->json([
            'success' => true,
            'items' => $cartItems,
            'totals' => [
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total' => $total
            ]
        ]);
    }

    public function removeFromCart($id)
    {
        $sessionId = session()->getId();
        $userId = Auth::check() ? Auth::id() : null;

        $cartItem = Cart::where('id', $id)
            ->when($userId, function($query) use ($userId) {
                return $query->where('user_id', $userId);
            }, function($query) use ($sessionId) {
                return $query->where('session_id', $sessionId)->whereNull('user_id');
            })
            ->first();

        if ($cartItem) {
            $cartItem->delete();
            return response()->json(['success' => true, 'message' => 'Item removed from cart']);
        }

        return response()->json(['success' => false, 'message' => 'Item not found'], 404);
    }

    public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $sessionId = session()->getId();
        $userId = Auth::check() ? Auth::id() : null;

        $cartItem = Cart::where('id', $id)
            ->when($userId, function($query) use ($userId) {
                return $query->where('user_id', $userId);
            }, function($query) use ($sessionId) {
                return $query->where('session_id', $sessionId)->whereNull('user_id');
            })
            ->first();

        if ($cartItem) {
            $cartItem->update(['quantity' => $request->quantity]);
            return response()->json(['success' => true, 'message' => 'Quantity updated']);
        }

        return response()->json(['success' => false, 'message' => 'Item not found'], 404);
    }

    public function clearCart()
    {
        $sessionId = session()->getId();
        $userId = Auth::check() ? Auth::id() : null;

        Cart::when($userId, function($query) use ($userId) {
            return $query->where('user_id', $userId);
        }, function($query) use ($sessionId) {
            return $query->where('session_id', $sessionId)->whereNull('user_id');
        })->delete();

        return response()->json(['success' => true, 'message' => 'Cart cleared']);
    }
}