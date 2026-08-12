<?php

namespace App\Http\Controllers;

use App\Support\AccessControl;
use App\Support\SiteSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    //
    public function checkout()
    {
        // WhatsApp-only ordering: there is no working checkout to show, so send
        // people back to the cart where the "Order on WhatsApp" button lives.
        // (Closes the door on typing /checkout directly.)
        if (SiteSettings::get('whatsapp_buy_now_enabled', '0') === '1') {
            return redirect('/cart');
        }

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
            return $item->cq * $item->price;
        });

        $shipping = 2.50; // Fixed shipping for now
        $tax = $subtotal * 0.00; // 5% tax
        $discount = 0.00; // Fixed discount for now
        $total = $subtotal + $shipping + $tax - $discount;
        // dd($cartItems);

        if(!$user) {
            // Guest checkout isn't supported — send them to login and bring
            // them back to checkout afterwards (relative URL, works on any domain).
            // With customer sign-in switched off there IS no login page, so the
            // cart (which carries the WhatsApp order button) is the right place.
            if (!AccessControl::loginAllowed('customer')) {
                return redirect('/cart');
            }
            return redirect('/login-user?type=customer-login&page=checkout');
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

    public function process(Request $request) {
        // Guests can't place orders — bounce to login (matches checkout()/buy()).
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'login_required' => true,
                'redirect' => AccessControl::loginAllowed('customer')
                    ? '/login-user?type=customer-login&page=checkout'
                    : '/cart',
                'message' => 'Please log in to place your order.'
            ], 401);
        }

        $user = Auth::user();
        $userId = $user->user_id;

        // Owner has switched the site to WhatsApp-only ordering (admin Controls
        // page) — block real order placement here, the single choke point both
        // the cart-checkout AND buy-now flows go through. No orders/carts rows
        // are touched.
        if (SiteSettings::get('whatsapp_buy_now_enabled', '0') === '1') {
            $pendingItems = DB::table('carts as c')
                ->join('vendor_products as vp', 'c.product_id', '=', 'vp.id')
                ->where('c.user_id', $userId)
                ->whereNull('c.order_id')
                ->select('vp.name', 'c.quantity', 'c.price')
                ->get();

            $lines = $pendingItems->map(function ($item) {
                return "- {$item->name} (x{$item->quantity}) — Rs. " . number_format($item->price * $item->quantity);
            })->implode("\n");

            $message = "Hi! I want to order:\n\n" . ($lines ?: 'My cart items') .
                "\n\nTotal: Rs. " . number_format($pendingItems->sum(fn($i) => $i->price * $i->quantity));

            return response()->json([
                'success' => false,
                'whatsapp_mode' => true,
                'whatsapp_url' => 'https://wa.me/' . SiteSettings::get('whatsapp_buy_now_number', '') . '?text=' . urlencode($message),
                'message' => 'Website ordering is currently paused — please complete your order on WhatsApp.',
            ], 409);
        }

        $totalFound = DB::table('carts')
            ->where('user_id', $userId)
            ->whereNull('order_id')
            ->count();

        // Real money math — mirrors the checkout view:
        //  - cart flow (CheckoutController::checkout): shipping 2.50, tax 0%, discount 0
        //  - buy-now flow (CartController::buy):       shipping 2.50, tax 5%, discount 15
        $subtotal = (float) DB::table('carts')
            ->where('user_id', $userId)
            ->whereNull('order_id')
            ->sum(DB::raw('price * quantity'));

        $shipping = 2.50;
        if ($request->input('single_buy')) {
            $tax = $subtotal * 0.05;
            $discount = 15.00;
        } else {
            $tax = $subtotal * 0.00;
            $discount = 0.00;
        }
        $totalAmount = round($subtotal + $shipping + $tax - $discount, 2);

        $orderId = DB::table('orders')->insertGetId([
            'user_id' => $userId,
            'quantity' => $totalFound,
            'subtotal' => round($subtotal, 2),
            'delivery_charges' => $shipping,
            'total_amount' => $totalAmount,
            'payment_method' => $request->input('payment_method', 'cod'),
            'order_date' => now(),
            'updated_at' => now()
        ]);

        // Then update
        $updatedCount = DB::table('carts')
            ->where('user_id', $userId)
            ->whereNull('order_id')
            ->update(['order_id' => $orderId]);


        DB::table('notifications')
            ->insert([
                'user_id' => $userId,
                'title' => "Order",
                'message' => "Your order has been send",
                'type' => "order",
                'icon_class' => "fas fa-shipping-fast",
                "icon_color" => "bg-pink-100 text-pink-600",
                "dot_color" => "bg-pink-500",
                "is_read" => 0,
                'created_at' => now()
            ]);

        // Get product IDs from carts
        $productIds = DB::table('carts')
            ->where('order_id', $orderId)
            ->pluck('product_id')
            ->toArray();

        // Map each ordered product to the vendor who actually owns it, so each
        // vendor is notified ONLY about their own products (not every product).
        $orderedProducts = DB::table('vendor_products')
            ->whereIn('id', $productIds)
            ->select('id', 'user_id', 'name')
            ->get();

        $notificationData = [];
        foreach ($orderedProducts as $vp) {
            $notificationData[] = [
                'user_id' => $vp->user_id,          // owner of THIS product
                'title' => "Order Received",
                'message' => "You have a new order for: " . ($vp->name ?: "PRD-{$vp->id}"),
                'type' => "order",
                'icon_class' => "fas fa-shipping-fast",
                "icon_color" => "bg-pink-100 text-pink-600",
                "dot_color" => "bg-pink-500",
                "is_read" => 0,
                'created_at' => now(),
            ];
        }

        // Insert all notifications at once
        if (!empty($notificationData)) {
            DB::table('notifications')->insert($notificationData);
        }

        return response()->json([
            'success' => true,
            'order_id' => $orderId
        ]);
    }
}
