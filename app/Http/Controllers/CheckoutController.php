<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    //
    public function checkout()
    {
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
            return "please <a href='https://arslan.mjcheezain.com/login-user?type=customer-login'> Register or login </a> your account";
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

    public function process() {
        $user = Auth::user();
        $userId = $user->user_id;

        $totalFound = DB::table('carts')
            ->where('user_id', $userId)
            ->whereNull('order_id')
            ->count();

        $orderId = DB::table('orders')->insertGetId([
            'user_id' => $userId,
            'quantity' => $totalFound,
            'total_amount' => 999,
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
                "icon_color" => "bg-blue-100 text-blue-600",
                "dot_color" => "bg-blue-500",
                "is_read" => 0,
                'created_at' => now()
            ]);

        // Get product IDs from carts
        $productIds = DB::table('carts')
            ->where('order_id', $orderId)
            ->pluck('product_id')
            ->toArray();

        // Get vendor user IDs from vendor_products
        $vendorIds = DB::table('vendor_products')
            ->whereIn('id', $productIds)
            ->pluck('user_id')
            ->toArray();

        // Prepare notification data for all vendors
        $notificationData = [];

        // For each vendor, create a notification for each product
        // This creates one notification per vendor per product
        foreach ($vendorIds as $vendorId) {
            foreach ($productIds as $productId) {
                $notificationData[] = [
                    'user_id' => $vendorId,
                    'title' => "Order Received",
                    'message' => "Your order of product PRD-{$productId}",
                    'type' => "order",
                    'icon_class' => "fas fa-shipping-fast",
                    "icon_color" => "bg-blue-100 text-blue-600",
                    "dot_color" => "bg-blue-500",
                    "is_read" => 0,
                    'created_at' => now(),
                    // 'updated_at' => now()
                ];
            }
        }

        // Insert all notifications at once
        if (!empty($notificationData)) {
            DB::table('notifications')->insert($notificationData);
        }

        return response()->json([
            'success' => $userId
        ]);
    }
}
