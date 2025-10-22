<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class VendorController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $vendor_id = $user->user_id;

        $vendorBasicInfo = DB::table('vendor_basic_info')
            ->where('user_id', $vendor_id)
            ->first();
        // dd($vendorBasicInfo);

        // ✅ Orders (last 6 months)
        $orders = DB::table('orders')
            ->selectRaw("DATE_FORMAT(order_date, '%Y-%m') as month, COUNT(*) as total_orders")
            ->where('vendor_id', $vendor_id)
            ->whereRaw("order_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Prepare 6-month chart data
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = date('Y-m', strtotime("-$i month"));
            $months[$m] = 0;
        }
        foreach ($orders as $o) {
            $months[$o->month] = $o->total_orders;
        }

        $chartLabels = array_map(fn($m) => date('M', strtotime("$m-01")), array_keys($months));
        $chartData = array_values($months);
        // dd($chartLabels, $chartData);

        // ✅ Vendor balance
        $balanceRow = DB::table('vendor_balance')
            ->where('user_id', $vendor_id)
            ->select('total_balance')
            ->first();
        $balance = $balanceRow->total_balance ?? 0.00;

        // ✅ Products (from vendor_products)
        $products = DB::table('vendor_products')
            ->where('user_id', $vendor_id)
            // ->select('id', 'name', 'selling_price', 'quantity', 'position', 'image_path')
            ->select('id', 'name', 'selling_price', 'quantity', 'position')
            ->orderByDesc('id')
            ->limit(8)
            ->get();

         // ✅ Stats Cards Data
        $totalProducts = DB::table('vendor_products')
            ->where('user_id', $vendor_id)
            ->count();

        $totalSales = DB::table('orders')
            ->where('vendor_id', $vendor_id)
            ->where('fulfillment', 'delivered')
            ->count();

        $newOrders = DB::table('orders')
            ->where('vendor_id', $vendor_id)
            ->count();

        // ✅ Recent Sold Orders (Last 3 orders)
        $recentOrders = DB::table('orders as o')
            ->select(
                'o.id as order_id',
                'p.name as product_name',
                'p.category as product_category',
                'o.total_amount',
                'o.order_date',
                'c.first_name as customer_name',
                'o.fulfillment',
                'pi.image_path'
            )
            ->join('vendor_products as p', 'o.product_id', '=', 'p.id')
            ->join('users as u', 'o.user_id', '=', 'u.user_id')
            ->leftJoin('customer_profile as c', 'o.user_id', '=', 'c.user_id')
            ->leftJoin('vendor_product_images as pi', function($join) {
                $join->on('pi.product_id', '=', 'p.id')
                     ->where('pi.is_primary', '=', true);
            })
            ->where('o.vendor_id', $vendor_id)
            ->orderBy('o.order_date', 'desc')
            ->limit(3)
            ->get()
            ->map(function($order) {
                return [
                    'order_id' => $order->order_id,
                    'product_name' => $order->product_name,
                    'product_category' => $order->product_category,
                    'total_amount' => $order->total_amount,
                    'order_date' => $order->order_date,
                    'customer_name' => $order->customer_name ?: 'N/A',
                    'fulfillment' => $order->fulfillment,
                    'image_path' => $order->image_path ? asset('uploads/' . $order->image_path) : asset('img/default_product.webp')
                ];
            })
            ->toArray();

        // ✅ Top Categories
        $topCategories = DB::table('vendor_products as p')
            ->select('p.name', DB::raw('COUNT(o.id) as order_count'))
            ->leftJoin('orders as o', function($join) use ($vendor_id) {
                $join->on('p.id', '=', 'o.product_id')
                     ->where('o.vendor_id', '=', $vendor_id);
            })
            ->where('p.user_id', $vendor_id)
            ->groupBy('p.id', 'p.name')
            ->orderBy('order_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function($category) {
                return [
                    'name' => $category->name,
                    'order_count' => $category->order_count
                ];
            })
            ->toArray();

        return view('vendor.dashboard', compact(
            'user', 'balance', 'products', 
            'chartLabels', 'chartData',
            'vendorBasicInfo',
            'totalProducts',
            'totalSales',
            'newOrders',
            'recentOrders',
            'topCategories'
        ));
    }


    public function profile()
    {
        $user = Auth::user();
        $vendor_id = $user->user_id;

        // Get vendor basic info
        $vendorBasicInfo = DB::table('vendor_basic_info')
            ->where('user_id', $vendor_id)
            ->first();

        // Get store info
        $storeInfo = DB::table('vendor_store_details')
            ->where('user_id', $vendor_id)
            ->first();

        return view('vendor.profile', [
            'profile_picture' => $vendorBasicInfo->profile_picture ?? asset('img/default-avatar.jpg'),
            'full_name' => $user->full_name ?? $user->username,
            'user_email' => $user->email,
            'phone' => $user->phone,
            'store_logo' => $storeInfo->store_logo ?? asset('img/default-store.png'),
            'store_name' => $storeInfo->store_name ?? 'My Store',
            'rating' => $storeInfo->rating ?? 0,
            'verified' => $storeInfo->verified ?? false,
            'city' => $storeInfo->city ?? 'Not specified',
            'country' => $storeInfo->country ?? 'Not specified',
            'store_banner' => $storeInfo->store_banner ?? asset('img/default-banner.jpg'),
            'pickup_address' => $storeInfo->pickup_address ?? 'Not specified',
            'business_type' => $storeInfo->business_type ?? 'Not specified',
            'store_category' => $storeInfo->store_category ?? 'Not specified',
            'return_policy' => $storeInfo->return_policy ?? 'Not avaliable',
            'return_policy_file' => $storeInfo->return_policy_file ?? 'Not avaliable',
            'shipping_policy' => $storeInfo->shipping_policy ?? 'Not avaliable',
            'shipping_policy_file' => $storeInfo->shipping_policy_file ?? 'Not avaliable',
            'store_description' => $storeInfo->store_description ?? 'No description provided.',
            'area' => $storeInfo->area ?? 'Not specified',
            'postal_code' => $storeInfo->postal_code ?? 'Not specified',
            'vendorBasicInfo' => $vendorBasicInfo,
        ]);
    }

    public function profileEdit() {
        $user = Auth::user();
        $vendor_id = $user->user_id;

        $vendorBasicInfo = DB::table('vendor_basic_info')
            ->where('user_id', $vendor_id)
            ->first();
        $storeDetail = DB::table('vendor_store_details')
            ->where('user_id', $vendor_id)
            ->first();
        $address = DB::table('vendor_address')
            ->where('user_id', $vendor_id)
            ->first();

        // dd($vendorBasicInfo);


        return view('vendor.edit-profile', compact(
            'vendorBasicInfo',
            'storeDetail',
            'address'
        ));
    }

    public function updateBasicInfo(Request $request)
    {
        try {
            $user = Auth::user();
            $vendor_id = $user->user_id;
            
            // Validate the request
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'store_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'profile_visibility' => 'nullable|boolean',
                'profile_picture' => 'nullable'
            ]);

            // Find the vendor
            $vendor = db::table('vendor_basic_info')
            ->where('user_id', $vendor_id)->first();
            
            if (!$vendor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor not found'
                ], 404);
            }

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                $profilePicture = $request->file('profile_picture');
                
                // Delete old profile picture if exists
                if ($vendor->profile_picture) {
                    Storage::delete('public/vendor/profile/' . $vendor->profile_picture);
                }
                
                // Generate unique filename
                $filename = 'vendor_' . $vendor_id . '_' . time() . '.' . $profilePicture->getClientOriginalExtension();
                
                // Store the image
                $path = $profilePicture->storeAs('vendor/profile', $filename);
                // $path = $profilePicture->move(public_path('storage/vendor/profile'));
                
                // Update filename in validated data
                $validated['profile_picture'] = $filename;
            } else {
                // Remove profile_picture from validated data if no new file uploaded
                unset($validated['profile_picture']);
            }
            
            // Convert profile_visibility to boolean
            $validated['profile_visibility'] = $request->boolean('profile_visibility');
            
            // Update vendor record
            DB::table('vendor_basic_info')
            ->where('user_id', $vendor_id)
            ->update($validated);
            // return response()->json(['success' => true]);
            
            
            return response()->json([
                'success' => true,
                'message' => 'Basic information updated successfully',
                'data' => $vendor
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating basic information: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStoreDetail(Request $request)
    {
        try {
            $user = Auth::user();
            $vendor_id = $user->user_id;

            // Validate fields
            $validated = $request->validate([
                'business_type' => 'nullable|string|max:100',
                'store_category' => 'nullable|string|max:100',
                'store_description' => 'nullable|string',
                'return_policy' => 'nullable|string',
                'shipping_policy' => 'nullable|string',
                'store_logo' => 'nullable',
                'store_banner' => 'nullable',
                'return_policy_file' => 'nullable',
                'shipping_policy_file' => 'nullable',
            ]);

            // Find vendor store record
            $vendor = DB::table('vendor_store_details')->where('user_id', $vendor_id)->first();
            if (!$vendor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor store details not found.'
                ], 404);
            }

            // Define base storage paths
            $basePath = 'vendor/store';

            // Handle each file upload
            if ($request->hasFile('store_logo')) {
                $file = $request->file('store_logo');
                $filename = 'logo_' . $vendor_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs($basePath, $filename);
                $validated['store_logo'] = $filename;
            }

            if ($request->hasFile('store_banner')) {
                $file = $request->file('store_banner');
                $filename = 'banner_' . $vendor_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs($basePath, $filename);
                $validated['store_banner'] = $filename;
            }

            if ($request->hasFile('return_policy_file')) {
                $file = $request->file('return_policy_file');
                $filename = 'return_policy_' . $vendor_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs($basePath, $filename);
                $validated['return_policy_file'] = $filename;
            }

            if ($request->hasFile('shipping_policy_file')) {
                $file = $request->file('shipping_policy_file');
                $filename = 'shipping_policy_' . $vendor_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs($basePath, $filename);
                $validated['shipping_policy_file'] = $filename;
            }

            // Update record
            DB::table('vendor_store_details')
                ->where('user_id', $vendor_id)
                ->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Store details updated successfully',
                'logo_url' => isset($validated['store_logo']) ? asset('storage/vendor/store/' . $validated['store_logo']) : null,
                'banner_url' => isset($validated['store_banner']) ? asset('storage/vendor/store/' . $validated['store_banner']) : null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating store details: ' . $e->getMessage()
            ], 500);
        }
    }
    public function updateAddress(Request $request)
    {
        // return response()->json([
        //         'success' => true,
        //         'message' => 'Address updated successfully!',
        //     ]);
        try {
            $user = Auth::user();
            $vendor_id = $user->user_id;

            // Validate form inputs
            $validated = $request->validate([
                'pickup_address' => 'required|string|max:255',
                'city' => 'required|string|max:100',
                'area' => 'required|string|max:100',
                'country' => 'required|string|max:100',
                'postal_code' => 'required|string|max:20',
            ]);


            // Check if vendor record exists
            $vendorAddress = DB::table('vendor_address')->where('user_id', $vendor_id)->first();

            if ($vendorAddress) {
                // Update existing record
                DB::table('vendor_address')->where('user_id', $vendor_id)->update($validated);
            } else {
                // Insert new record if not found
                $validated['user_id'] = $vendor_id;
                DB::table('vendor_address')->insert($validated);
            }

            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating address: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function products(Request $request) {
        $user = Auth::user();
        $vendor_id = $user->user_id;
        
        // Get vendor basic info
        $vendorBasicInfo = DB::table('vendor_basic_info')
            ->where('user_id', $vendor_id)
            ->first();
        
        // Default values if data doesn't exist
        // $profile_picture = $vendorBasicInfo->profile_picture ?? 'img/default_profile.webp';
        // $full_name = $vendorBasicInfo->full_name ?? 'Not specified';
        
        // Determine active tab from URL parameter
        $active_tab = $request->get('tab', 'all');
        
        // Map tab names to position values
        $tab_position_map = [
            'online' => 'online',
            'pending' => 'pending',
            'offline' => 'offline',
            'draft' => 'draft',
            'all' => 'all'
        ];
        
        // Get products based on active tab
        $query = DB::table('vendor_products')
        ->leftJoin('vendor_product_images as primaryImage', 'vendor_products.id', '=', 'primaryImage.product_id')
        ->where('vendor_products.user_id', $vendor_id)
        ->select('vendor_products.*', 'primaryImage.image_path as primary_image');

        
        if ($active_tab !== 'all') {
            $position = $tab_position_map[$active_tab] ?? 'all';
            $query->where('position', $position);
        }
        
        $products = $query->get();
        $total_products = $products->count();
        $pending_products = $products->where('position', 'pending')->count();
        
        // Calculate completion percentage
        $completion_percentage = $total_products > 0 
            ? round(100 - (($pending_products / $total_products) * 100))
            : 0;
        
        return view('vendor.products', compact(
            'vendorBasicInfo',
            'products',
            'active_tab',
            'total_products',
            'pending_products',
            'completion_percentage'
        ));
    }

    public function productsCreate () {}
    public function pr ($pr = 1) {}

}
