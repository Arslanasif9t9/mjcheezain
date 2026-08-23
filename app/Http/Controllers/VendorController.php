<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\VendorBasicInfo;
use App\Models\VendorProduct;
use App\Models\VendorProductImage;
use App\Models\VendorProductFault;
use App\Http\Controllers\Vendor\AutoPartsProductController;

class VendorController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        // dd($user->full_name);
        $vendor_id = $user->user_id;

        $vendorBasicInfo = DB::table('vendor_basic_info')
            ->where('user_id', $vendor_id)
            ->first();
        // dd($vendorBasicInfo);

        // ✅ Orders (last 6 months)
        $driver = DB::connection()->getDriverName();
        if ($driver === 'sqlite') {
            $orders = DB::table('orders')
                ->selectRaw("strftime('%Y-%m', order_date) as month, COUNT(*) as total_orders")
                ->where('vendor_id', $vendor_id)
                ->whereRaw("order_date >= date('now', '-6 month')")
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        } else {
            $orders = DB::table('orders')
                ->selectRaw("DATE_FORMAT(order_date, '%Y-%m') as month, COUNT(*) as total_orders")
                ->where('vendor_id', $vendor_id)
                ->whereRaw("order_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)")
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        }

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

        // ✅ Vendor balance (real value from vendor_balances; 0 if no wallet row yet)
        $balanceRow = DB::table('vendor_balances')
            ->where('vendor_id', $vendor_id)
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
                    'image_path' => $order->image_path ? asset('storage/vendor/products/images/' . $order->image_path) : asset('img/default_img.png')
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

        // Get address info
        $vendorAddress = DB::table('vendor_address')
            ->where('user_id', $vendor_id)
            ->first();

        return view('vendor.profile', [
            'user' => $user,
            'profile_picture' => $vendorBasicInfo->profile_picture ?? 'default_profile.webp',
            'full_name' => $user->full_name ?? $user->username,
            'user_email' => $user->email,
            'phone' => $user->phone ?? $vendorBasicInfo->phone ?? null,
            'store_logo' => $storeInfo->store_logo ?? 'default_profile.webp',
            'store_name' => $storeInfo->store_name ?? 'My Store',
            'rating' => $storeInfo->rating ?? 0,
            'verified' => $storeInfo->verified ?? false,
            'city' => $vendorAddress->city ?? 'Not specified',
            'country' => $vendorAddress->country ?? 'Not specified',
            'store_banner' => $storeInfo->store_banner ?? asset('img/default-banner.jpg'),
            'pickup_address' => $vendorAddress->pickup_address ?? 'Not specified',
            'business_type' => $storeInfo->business_type ?? 'Not specified',
            'store_category' => $storeInfo->store_category ?? 'Not specified',
            'return_policy' => $storeInfo->return_policy ?? 'Not avaliable',
            'return_policy_file' => $storeInfo->return_policy_file ?? 'Not avaliable',
            'shipping_policy' => $storeInfo->shipping_policy ?? 'Not avaliable',
            'shipping_policy_file' => $storeInfo->shipping_policy_file ?? 'Not avaliable',
            'store_description' => $storeInfo->store_description ?? 'No description provided.',
            'area' => $vendorAddress->area ?? 'Not specified',
            'postal_code' => $vendorAddress->postal_code ?? 'Not specified',
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
            "user",
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
                'store_name' => 'string|max:255',
                'phone' => 'max:20',
                'profile_visibility' => 'nullable|boolean',
                'profile_picture' => 'nullable'
            ]);

            // Find the vendor
            $vendor = db::table('vendor_basic_info')
            ->where('user_id', $vendor_id)->first();
            
            if (!$vendor) {
                VendorBasicInfo::create([
                    'user_id' => $vendor_id,
                    'full_name' => $request->full_name
                ]);
                // Re-fetch so the rest of the method has a real row to work with
                $vendor = db::table('vendor_basic_info')
                    ->where('user_id', $vendor_id)->first();
            }

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                $profilePicture = $request->file('profile_picture');

                // Delete old profile picture if exists
                if ($vendor && $vendor->profile_picture) {
                    Storage::delete('public/vendor/profile/' . $vendor->profile_picture);
                }
                
                // Generate unique filename
                $filename = 'vendor_' . $vendor_id . '_' . time() . '.' . $profilePicture->getClientOriginalExtension();
                
                // Store the image
                $path = $profilePicture->storeAs('vendor/profile', $filename, 'public');
                $destinationPath = public_path('storage/vendor/profile');
                $profilePicture->move($destinationPath, $filename);
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
                'message' => \App\Support\ErrorReason::friendly($e, 'Error updating basic information')
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
                // return response()->json([
                //     'success' => false,
                //     'message' => 'Vendor store details not found.'
                // ], 404);
                DB::table('vendor_store_details')->insert(['user_id'=>$vendor_id]);
            }

            // Define base storage paths
            $basePath = 'vendor/store';

            // Handle each file upload
            if ($request->hasFile('store_logo')) {
                $file = $request->file('store_logo');
                $filename = 'logo_' . $vendor_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs($basePath, $filename);
                $destinationPath = public_path('storage/vendor/store');
                $file->move($destinationPath, $filename);
                $validated['store_logo'] = $filename;
            }

            if ($request->hasFile('store_banner')) {
                $file = $request->file('store_banner');
                $filename = 'banner_' . $vendor_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs($basePath, $filename);
                $destinationPath = public_path('storage/vendor/store');
                $file->move($destinationPath, $filename);
                $validated['store_banner'] = $filename;
            }

            if ($request->hasFile('return_policy_file')) {
                $file = $request->file('return_policy_file');
                $filename = 'return_policy_' . $vendor_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs($basePath, $filename);
                $destinationPath = public_path('storage/vendor/store');
                $file->move($destinationPath, $filename);
                $validated['return_policy_file'] = $filename;
            }

            if ($request->hasFile('shipping_policy_file')) {
                $file = $request->file('shipping_policy_file');
                $filename = 'shipping_policy_' . $vendor_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs($basePath, $filename);
                $destinationPath = public_path('storage/vendor/store');
                $file->move($destinationPath, $filename);
                $validated['shipping_policy_file'] = $filename;
            }

            // Update record
            DB::table('vendor_store_details')
                ->where('user_id', $vendor_id)
                ->update($validated);

            if($request->NTN != null){
                DB::table('vendor_store_details')
                    ->where('user_id', $vendor_id)
                    ->update(['NTN' => $request->NTN]);
            }

            

            return response()->json([
                'success' => true,
                'message' => 'Store details updated successfully',
                'logo_url' => isset($validated['store_logo']) ? asset('storage/vendor/store/' . $validated['store_logo']) : null,
                'banner_url' => isset($validated['store_banner']) ? asset('storage/vendor/store/' . $validated['store_banner']) : null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => \App\Support\ErrorReason::friendly($e, 'Error updating store details')
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
                'pickup_address' => 'max:255',
                'city' => 'max:100',
                'area' => 'max:100',
                'country' => 'max:100',
                'postal_code' => 'max:20',
            ]);


            // Check if vendor record exists
            $vendorAddress = DB::table('vendor_address')->where('user_id', $vendor_id)->first();
            if (!$vendorAddress) {
                DB::table('vendor_address')->insert(['user_id'=>$vendor_id]);
            }

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
                'message' => \App\Support\ErrorReason::friendly($e, 'Error updating address'),
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
        // $active_tab = $request->get('tab', 'all');
        
        // Map tab names to position values
        // $tab_position_map = [
        //     'online' => 'online',
        //     'pending' => 'pending',
        //     'offline' => 'offline',
        //     'draft' => 'draft',
        //     'all' => 'all'
        // ];
        
        // Get products based on active tab
        // $query = DB::table('vendor_products')
        // ->leftJoin('vendor_product_images as primaryImage', 'vendor_products.id', '=', 'primaryImage.product_id')
        // ->where('vendor_products.user_id', $vendor_id)
        // ->select('vendor_products.*', 'primaryImage.image_path as primary_image');
        $products = VendorProduct::where('user_id', $vendor_id)->get();

        foreach ($products as $product) {
            $productImage = VendorProductImage::where('product_id', $product->id)
                                            ->where('is_primary', 1)
                                            ->first(); // use first() because only one primary image

            // Example: attach image path to product
            $product->primary_image = $productImage ? $productImage->image_path : null;
        }
        
        // if ($active_tab !== 'all') {
        //     $position = $tab_position_map[$active_tab] ?? 'all';
        //     $query->where('position', $position);
        // }
        
        // $products = $query->get();
        $total_products = $products->count();
        $pending_products = $products->where('position', 'pending')->count();
        
        // Calculate completion percentage
        $completion_percentage = $total_products > 0 
            ? round(100 - (($pending_products / $total_products) * 100))
            : 0;
        
        $active_tab = null;
        return view('vendor.products', compact(
            'user',
            'vendorBasicInfo',
            'products',
            'active_tab',
            'total_products',
            'pending_products',
            'completion_percentage'
        ));
    }

    public function productsCreate ($id = null) {
        $user = Auth::user();
        $vendor_id = $user->user_id;
        
        // Get vendor basic info
        $vendorBasicInfo = DB::table('vendor_basic_info')
            ->where('user_id', $vendor_id)
            ->first();

        $product = null;
        $productImages = [];
        $productFaults = [];
        // Men's Fashion (and later, the other fashion categories) prefill data.
        // TODO: same pattern for Women's Fashion / Kids & Baby / Footwear / Bags & Accessories
        $faAttrs = [];
        $faSizes = [];
        // Jewellery & Accessories prefill data.
        $jaAttrs = [];
        // Fragrance & Scents / Bags & Luggage / Personal Gym Accessories /
        // Kitchen & Dining / Smart Home & Gadgets / Personal Care & Daily
        // Essentials prefill data.
        $frAttrs = [];
        $bgAttrs = [];
        $gmAttrs = [];
        $ktAttrs = [];
        $smAttrs = [];
        $pcAttrs = [];

        // If editing, get product data
        if ($id) {
            $product = DB::table('vendor_products')
                ->where('id', $id)
                ->where('user_id', $vendor_id)
                ->first();

            if ($product) {
                // Get product images
                $productImages = DB::table('vendor_product_images')
                    ->where('product_id', $id)
                    ->get();

                // Get product faults
                $productFaults = DB::table('vendor_product_faults')
                    ->where('product_id', $id)
                    ->get();

                // Decode fashion_attributes JSON for the form to prefill (raw
                // DB::table() query, so no Eloquent cast applies here).
                if (!empty($product->fashion_attributes)) {
                    $decoded = json_decode($product->fashion_attributes, true) ?: [];
                    $faSizes = $decoded['sizes'] ?? [];
                    unset($decoded['sizes']);
                    $faAttrs = $decoded;
                }

                // Decode jewelry_attributes JSON for the form to prefill.
                if (!empty($product->jewelry_attributes)) {
                    $jaAttrs = json_decode($product->jewelry_attributes, true) ?: [];
                }

                // Decode the 6 new categories' JSON attribute columns for the
                // form to prefill (same pattern as jewelry_attributes above).
                if (!empty($product->fragrance_attributes)) {
                    $frAttrs = json_decode($product->fragrance_attributes, true) ?: [];
                }
                if (!empty($product->bags_attributes)) {
                    $bgAttrs = json_decode($product->bags_attributes, true) ?: [];
                }
                if (!empty($product->gym_attributes)) {
                    $gmAttrs = json_decode($product->gym_attributes, true) ?: [];
                }
                if (!empty($product->kitchen_attributes)) {
                    $ktAttrs = json_decode($product->kitchen_attributes, true) ?: [];
                }
                if (!empty($product->smarthome_attributes)) {
                    $smAttrs = json_decode($product->smarthome_attributes, true) ?: [];
                }
                if (!empty($product->personalcare_attributes)) {
                    $pcAttrs = json_decode($product->personalcare_attributes, true) ?: [];
                }
            }
        }


        return view('vendor.new_product', compact(
            'user',
            'vendorBasicInfo',
            'product',
            'productImages',
            'productFaults',
            'faAttrs',
            'faSizes',
            'jaAttrs',
            'frAttrs',
            'bgAttrs',
            'gmAttrs',
            'ktAttrs',
            'smAttrs',
            'pcAttrs'
        ));
    }

    public function storeProduct(Request $request)
    {
        // Validate the request
        // $validated = $request->validate([
        //     // 'productVideo' => 'required|string|max:255',
        //     'product_name' => 'required|string|max:255',
        //     'category' => 'required|string|max:255',
        //     'subcategory' => 'required|string|max:255',
        //     'quantity' => 'required|integer|min:1',
        //     'brand' => 'required|string|max:255',
        //     'model' => 'nullable|string|max:255',
        //     // 'model_value' => 'nullable|string|max:255',
        //     // 'model_unit' => 'nullable|string|max:255',
        //     'condition' => 'required|string|max:255',
        //     'original_price' => 'required|numeric|min:0',
        //     'delivery_charges' => 'required|numeric|min:0',
        //     'selling_price' => 'required|numeric|min:0',
        //     'mrp' => 'nullable|numeric|min:0',
        //     'shipping_method' => 'required|string|max:255',
        //     'shipping_time' => 'required|string|max:255',
        //     'description' => 'required|string|min:100',
        //     'location' => 'required|string|max:255',
        //     'made_in' => 'required|string|max:255',
        //     'return_policy' => 'nullable|string',
        //     'productImages' => 'required|array|min:5|max:10',
        //     'productImages.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        //     'productVideo' => 'nullable|file|mimes:mp4,mov,avi|max:51200',
        //     'faults' => 'nullable|array',
        //     'faults.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        //     'fault_descriptions' => 'nullable|array',
        // ]);
        
        $validated = $request->all();
        // return response()->json([
        //         'success' => $request->all()
        //     ]);

        // Draft: saved with status/position 'draft' — storefront (position='approved')
        // and admin moderation (position='pending') never see it.
        $isDraft = $request->boolean('save_as_draft');

        // "Other" category: use the vendor-typed value and queue it for admin review
        $customCategory = $this->resolveCustomCategory($request, $validated);

        // Men's Fashion: collect the common + category-specific fields into
        // one JSON blob (vendor_products.fashion_attributes). Other
        // categories are unaffected.
        // TODO: same pattern for Women's Fashion / Kids & Baby / Footwear / Bags & Accessories
        $fashionAttributes = $this->buildFashionAttributes($request, $validated);

        // Jewellery & Accessories: collect the common + subcategory-specific
        // fields into one JSON blob (vendor_products.jewelry_attributes).
        $jewelryAttributes = $this->buildJewelryAttributes($request, $validated);

        // Fragrance & Scents: common + subcategory-specific fields.
        $fragranceAttributes = $this->buildFragranceAttributes($request, $validated);

        // Bags & Luggage / Personal Gym Accessories / Kitchen & Dining / Smart
        // Home & Gadgets / Personal Care & Daily Essentials: each is a single
        // common-fields block, built by one shared helper. Only the call whose
        // category matches $validated['category'] returns non-null.
        $bagsAttributes = $this->buildSharedCategoryAttributes($request, $validated, 'Bags & Luggage', 'bg', 'gender');
        $gymAttributes = $this->buildSharedCategoryAttributes($request, $validated, 'Personal Gym Accessories', 'gm', 'weight');
        $kitchenAttributes = $this->buildSharedCategoryAttributes($request, $validated, 'Kitchen & Dining', 'kt', 'weight');
        $smarthomeAttributes = $this->buildSharedCategoryAttributes($request, $validated, 'Smart Home & Gadgets', 'sm', 'weight');
        $personalcareAttributes = $this->buildSharedCategoryAttributes($request, $validated, 'Personal Care & Daily Essentials', 'pc', 'weight');

        $validated['product_name'] = ucwords(trim($validated['product_name'] ?? ''));
        $validated['brand'] = ucwords(trim($validated['brand'] ?? ''));
        $validated['model'] = ucwords(trim($validated['model'] ?? ''));
        $validated['made_in'] = ucwords(trim($validated['made_in'] ?? ''));
        // Handle model field based on category
        // $modelValue = $validated['model'] ?? '';
        // if (!empty($validated['model_value']) && !empty($validated['model_unit'])) {
        //     $modelValue = $validated['model_value'] . ' ' . $validated['model_unit'];
        // }

        // Create the product (drafts may have empty fields — NOT NULL columns get safe defaults)
        $product = VendorProduct::create([
            'user_id' => Auth::id(),
            'name' => $validated['product_name'] !== '' ? $validated['product_name'] : 'Untitled Draft',
            'category' => $validated['category'] ?? '',
            'subcategory' => $validated['subcategory'] ?? '',
            'quantity' => (int) ($validated['quantity'] ?? 0),
            'brand' => $validated['brand'],
            'model' => $validated['model'],
            'pcondition' => $validated['condition'] ?? '',
            'original_price' => 0,
            'delivery_charges' => (float) ($validated['delivery_charges'] ?? 0),
            'free_delivery' => $request->boolean('free_delivery'),
            'selling_price' => (float) ($validated['selling_price'] ?? 0),
            'mrp' => ($validated['mrp'] ?? '') !== '' ? $validated['mrp'] : null,
            'shipping_method' => $validated['shipping_method'] ?? '',
            'shipping_time' => $validated['shipping_time'] ?? '',
            'description' => $validated['description'] ?? '',
            'location' => $validated['location'] ?? '',
            'made_in' => $validated['made_in'],
            'return_policy' => $validated['return_policy'] ?? null,
            'status' => $isDraft ? 'draft' : 'pending',
            'position' => $isDraft ? 'draft' : 'pending',
            'part_type' => $validated['part_type'] ?? null,
            'fashion_attributes' => $fashionAttributes,
            'jewelry_attributes' => $jewelryAttributes,
            'fragrance_attributes' => $fragranceAttributes,
            'bags_attributes' => $bagsAttributes,
            'gym_attributes' => $gymAttributes,
            'kitchen_attributes' => $kitchenAttributes,
            'smarthome_attributes' => $smarthomeAttributes,
            'personalcare_attributes' => $personalcareAttributes,
        ]);

        if ($customCategory) {
            $this->queueCategorySuggestion($validated, $product->id);
        }

        // Men's Fashion: size guide/chart image (fashion_attributes.size_guide)
        $this->storeSizeGuideUpload($request, $product);

        // return $request->input('productImages');
        // Handle product images
        if ($request->hasFile('productImages')) {
            foreach ($request->file('productImages') as $index => $image) {
                $extension = $image->getClientOriginalExtension();
                $filename = uniqid() . '.' . $extension; // unique filename
                $imagePath = $image->storeAs('vendor/products/images', $filename, 'public');
                $destinationPath = public_path('storage/vendor/products/images');
                $image->move($destinationPath, $filename);
                
                VendorProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $filename,
                    'is_primary' => $index === 0, // First image is primary
                ]);
            }
        }

        // Handle product video
        if ($request->hasFile('productVideo')) {
            $video = $request->file('productVideo');
            $extension = $video->getClientOriginalExtension(); // was $image — saved videos with an image extension (or crashed with no images)
            $filename = uniqid() . '.' . $extension; // unique filename
            $videoPath = $video->storeAs('vendor/products/videos', $filename, 'public');
            $destinationPath = public_path('storage/vendor/products/videos');
            $video->move($destinationPath, $filename);
            VendorProduct::where('id', $product->id)
                ->update([
                    'video' => $filename
                ]);
        }

        // Handle product faults
        if ($request->hasFile('faults')) {
            foreach ($request->file('faults') as $index => $faultImage) {
                $extension = $faultImage->getClientOriginalExtension();
                $filename = uniqid() . '.' . $extension; // unique filename
                $faultImagePath = $faultImage->storeAs('vendor/products/faults', $filename, 'public');
                $destinationPath = public_path('storage/vendor/products/faults');
                $faultImage->move($destinationPath, $filename);
                
                VendorProductFault::create([
                    'product_id' => $product->id,
                    'fault_image' => $filename,
                    'fault_description' => $validated['fault_descriptions'][$index] ?? null,
                ]);
            }
        }
        
        if (!$isDraft && ($validated['part_type'] ?? null)) {
            $AutoPartsProductController = new AutoPartsProductController();
            $result = $AutoPartsProductController->store($validated, $product->id);
            // return response()->json(['test'=>$result]);
        }

        $message = $isDraft
            ? 'Product saved as draft. You can finish and publish it anytime from My Products.'
            : 'Product created successfully and is pending approval.';

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('vendor.products')
            ]);
        }

        return redirect()->route('vendor.products')
            ->with('success', $message);
    }

    /**
     * "Other" category support: when the vendor picks Other, swap in the typed
     * category/subcategory. Returns the typed category (or null if not used).
     */
    private function resolveCustomCategory(Request $request, array &$validated)
    {
        if (($validated['category'] ?? '') !== '__other__') {
            return null;
        }

        $custom = ucwords(trim((string) $request->input('custom_category', '')));
        $validated['category'] = $custom !== '' ? $custom : 'Other';

        $customSub = ucwords(trim((string) $request->input('custom_subcategory', '')));
        $validated['subcategory'] = $customSub !== '' ? $customSub : 'General';

        return $validated['category'];
    }

    /**
     * Men's Fashion: collect the common + category-specific fields (that
     * don't already have a real vendor_products column) into one array to
     * be stored as vendor_products.fashion_attributes (JSON). Returns null
     * for every other category so their product creation is unaffected.
     * TODO: same pattern for Women's Fashion / Kids & Baby / Footwear / Bags & Accessories
     */
    private function buildFashionAttributes(Request $request, array $validated): ?array
    {
        if (($validated['category'] ?? '') !== "Men's Fashion") {
            return null;
        }

        $attrs = [
            // Common fashion fields (step 3 of the task: fields with no existing column)
            'sku' => trim((string) $request->input('fa_sku', '')) ?: null,
            'material' => trim((string) $request->input('fa_material', '')) ?: null,
            'color' => trim((string) $request->input('fa_color', '')) ?: null,
            'pattern' => trim((string) $request->input('fa_pattern', '')) ?: null,
            'availability' => $request->input('fa_availability') === 'Out of Stock' ? 'Out of Stock' : 'In Stock',
            'weight' => trim((string) $request->input('fa_weight', '')) ?: null,
            'warranty' => $request->input('fa_warranty') === 'Yes' ? 'Yes' : 'No',
            'warranty_duration' => $request->input('fa_warranty') === 'Yes'
                ? (trim((string) $request->input('fa_warranty_duration', '')) ?: null)
                : null,
            'returnable' => $request->input('fa_returnable') === 'No' ? 'No' : 'Yes',
            'return_exchange_policy' => $request->input('fa_returnable') !== 'No'
                ? (trim((string) $request->input('fa_return_exchange_policy', '')) ?: null)
                : null,
            'shipping_info' => trim((string) $request->input('fa_shipping_info', '')) ?: null,
            'tags' => trim((string) $request->input('fa_tags', '')) ?: null,
            'care_instructions' => trim((string) $request->input('fa_care_instructions', '')) ?: null,

            // Men's Fashion category-specific fields (step 4 of the task)
            'clothing_type' => $request->input('fa_clothing_type') ?: null,
            'fit' => $request->input('fa_fit') ?: null,
            'sleeve_type' => $request->input('fa_sleeve_type') ?: null,
            'neck_type' => $request->input('fa_neck_type') ?: null,
            'clothing_length' => $request->input('fa_clothing_length') ?: null,
            'season' => $request->input('fa_season') ?: null,
            'gender' => 'Men', // implied by category, still stored explicitly
            'occasion' => $request->input('fa_occasion') ?: null,
        ];

        // Size + stock repeater — parallel arrays fa_size_name[] / fa_size_stock[]
        $sizeNames = (array) $request->input('fa_size_name', []);
        $sizeStocks = (array) $request->input('fa_size_stock', []);
        $sizes = [];
        foreach ($sizeNames as $i => $size) {
            $size = trim((string) $size);
            if ($size === '') {
                continue;
            }
            $sizes[] = [
                'size' => $size,
                'stock' => (int) ($sizeStocks[$i] ?? 0),
            ];
        }
        $attrs['sizes'] = $sizes;

        return $attrs;
    }

    /**
     * Men's Fashion: optional size guide/chart image upload, stored under
     * fashion_attributes.size_guide (filename only, mirrors how product
     * video/images are stored). Safe no-op for non-Men's-Fashion products
     * or when no file was submitted.
     */
    private function storeSizeGuideUpload(Request $request, VendorProduct $product): void
    {
        if (!$request->hasFile('fa_size_guide')) {
            return;
        }

        $file = $request->file('fa_size_guide');
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('vendor/products/size_guides', $filename, 'public');
        $destinationPath = public_path('storage/vendor/products/size_guides');
        $file->move($destinationPath, $filename);

        $attrs = $product->fashion_attributes ?? [];
        $attrs['size_guide'] = $filename;
        $product->update(['fashion_attributes' => $attrs]);
    }

    /**
     * Jewellery & Accessories: collect the common + subcategory-specific
     * fields into one array to be stored as vendor_products.jewelry_attributes
     * (JSON). Returns null for every other category so their product
     * creation/update is unaffected. Same pattern as buildFashionAttributes().
     */
    private function buildJewelryAttributes(Request $request, array $validated): ?array
    {
        if (($validated['category'] ?? '') !== 'Jewellery & Accessories') {
            return null;
        }

        $yesNo = fn ($input) => $input === 'Yes' ? 'Yes' : 'No';

        $attrs = [
            // Common jewellery fields (shared across all subcategories)
            'material' => $request->input('jw_material') ?: null,
            'purity' => $request->input('jw_purity') ?: null,
            'weight' => trim((string) $request->input('jw_weight', '')) ?: null,
            'color' => trim((string) $request->input('jw_color', '')) ?: null,
            'gender' => $request->input('jw_gender') ?: null,
            'warranty' => $yesNo($request->input('jw_warranty')),
            'warranty_duration' => $request->input('jw_warranty') === 'Yes'
                ? (trim((string) $request->input('jw_warranty_duration', '')) ?: null)
                : null,
        ];

        $subcategory = $validated['subcategory'] ?? '';

        switch ($subcategory) {
            case 'Rings':
                $attrs['ring_size'] = trim((string) $request->input('jws_ring_size', '')) ?: null;
                $attrs['ring_stone_included'] = $yesNo($request->input('jws_ring_stone_included'));
                $attrs['ring_stone_type'] = $attrs['ring_stone_included'] === 'Yes'
                    ? ($request->input('jws_ring_stone_type') ?: null)
                    : null;
                break;

            case 'Necklace':
                $attrs['necklace_length'] = trim((string) $request->input('jws_necklace_length', '')) ?: null;
                $attrs['necklace_pendant_included'] = $yesNo($request->input('jws_necklace_pendant_included'));
                $attrs['necklace_pendant_type'] = $attrs['necklace_pendant_included'] === 'Yes'
                    ? ($request->input('jws_necklace_pendant_type') ?: null)
                    : null;
                $attrs['necklace_stone_included'] = $yesNo($request->input('jws_necklace_stone_included'));
                $attrs['necklace_stone_type'] = $attrs['necklace_stone_included'] === 'Yes'
                    ? ($request->input('jws_necklace_stone_type') ?: null)
                    : null;
                break;

            case 'Earrings':
                $attrs['earring_type'] = $request->input('jws_earring_type') ?: null;
                $attrs['earring_color'] = $request->input('jws_earring_color') ?: null;
                $attrs['earring_stone_included'] = $yesNo($request->input('jws_earring_stone_included'));
                $attrs['earring_stone_type'] = $attrs['earring_stone_included'] === 'Yes'
                    ? ($request->input('jws_earring_stone_type') ?: null)
                    : null;
                $attrs['earring_stone_color'] = $attrs['earring_stone_included'] === 'Yes'
                    ? ($request->input('jws_earring_stone_color') ?: null)
                    : null;
                break;

            case 'Bangles':
                $attrs['bangle_size'] = trim((string) $request->input('jws_bangle_size', '')) ?: null;
                $attrs['bangle_qty'] = $request->input('jws_bangle_qty') ?: null;
                break;

            case 'Chain':
                $attrs['chain_length'] = $request->input('jws_chain_length') ?: null;
                $attrs['chain_style'] = $request->input('jws_chain_style') ?: null;
                break;

            case 'Pendants':
                $attrs['pendant_shape'] = trim((string) $request->input('jws_pendant_shape', '')) ?: null;
                $attrs['pendant_theme'] = $request->input('jws_pendant_theme') ?: null;
                $attrs['pendant_stone_type'] = $request->input('jws_pendant_stone_type') ?: null;
                $attrs['pendant_chain_included'] = $yesNo($request->input('jws_pendant_chain_included'));
                break;

            case 'Anklets':
                $attrs['anklet_length'] = trim((string) $request->input('jws_anklet_length', '')) ?: null;
                $attrs['anklet_qty'] = $request->input('jws_anklet_qty') ?: null;
                $attrs['anklet_stone_type'] = $request->input('jws_anklet_stone_type') ?: null;
                break;

            case 'Nose Pins':
                $attrs['nosepin_type'] = $request->input('jws_nosepin_type') ?: null;
                $attrs['nosepin_stone_type'] = $request->input('jws_nosepin_stone_type') ?: null;
                break;

            case 'Brooches':
                $attrs['brooch_shape'] = trim((string) $request->input('jws_brooch_shape', '')) ?: null;
                $attrs['brooch_stone_type'] = $request->input('jws_brooch_stone_type') ?: null;
                break;

            case 'Charms':
                $attrs['charm_type'] = $request->input('jws_charm_type') ?: null;
                $attrs['charm_compatible'] = array_values(array_filter((array) $request->input('jws_charm_compatible', [])));
                $attrs['charm_stone_included'] = $yesNo($request->input('jws_charm_stone_included'));
                $attrs['charm_stone_type'] = $attrs['charm_stone_included'] === 'Yes'
                    ? ($request->input('jws_charm_stone_type') ?: null)
                    : null;
                break;

            case 'Jewelry Sets':
                $attrs['set_includes'] = array_values(array_filter((array) $request->input('jws_set_includes', [])));
                $attrs['set_pieces'] = $request->input('jws_set_pieces') !== null && $request->input('jws_set_pieces') !== ''
                    ? (int) $request->input('jws_set_pieces')
                    : null;
                $attrs['set_stone_type'] = $request->input('jws_set_stone_type') ?: null;
                $attrs['set_occasion'] = $request->input('jws_set_occasion') ?: null;
                $attrs['set_certification'] = trim((string) $request->input('jws_set_certification', '')) ?: null;
                break;
        }

        return $attrs;
    }

    /**
     * Fragrance & Scents: collect the common + subcategory-specific fields
     * into one array to be stored as vendor_products.fragrance_attributes
     * (JSON). Returns null for every other category. Same pattern as
     * buildJewelryAttributes() — 4 of the 6 subcategories add one extra
     * field/pair on top of the common fields; Attars and Perfume Oils SHARE
     * the "Alcohol Free" field; Body Mists has no extra field.
     */
    private function buildFragranceAttributes(Request $request, array $validated): ?array
    {
        if (($validated['category'] ?? '') !== 'Fragrance & Scents') {
            return null;
        }

        $attrs = [
            // Common fragrance fields (shared across all subcategories)
            'volume' => trim((string) $request->input('fr_volume', '')) ?: null,
            'gender' => $request->input('fr_gender') ?: null,
            'warranty' => $request->input('fr_warranty') === 'Yes' ? 'Yes' : 'No',
            'warranty_duration' => $request->input('fr_warranty') === 'Yes'
                ? (trim((string) $request->input('fr_warranty_duration', '')) ?: null)
                : null,
        ];

        switch ($validated['subcategory'] ?? '') {
            case 'Perfumes':
                $attrs['fragrance_type'] = $request->input('frs_fragrance_type') ?: null;
                break;

            case 'Attars':
            case 'Perfume Oils':
                $attrs['alcohol_free'] = $request->input('frs_alcohol_free') === 'Yes' ? 'Yes' : 'No';
                break;

            case 'Deodorants':
                $attrs['deodorant_type'] = $request->input('frs_deodorant_type') ?: null;
                break;

            case 'Gift Sets':
                $attrs['included_items'] = array_values(array_filter((array) $request->input('frs_included_items', [])));
                $attrs['number_of_items'] = $request->input('frs_number_of_items') ?: null;
                break;

            // Body Mists: no extra field beyond the common ones above.
        }

        return $attrs;
    }

    /**
     * Shared builder for the 5 categories that only need one common-fields
     * block (no per-subcategory forms): Bags & Luggage, Personal Gym
     * Accessories, Kitchen & Dining, Smart Home & Gadgets, Personal Care &
     * Daily Essentials. Each has the same field shape (material, color,
     * size, warranty) plus either "gender" (Bags) or "weight" (the other 4),
     * so one config-driven helper replaces 5 near-identical build*Attributes()
     * methods. Returns null unless $validated['category'] matches $category.
     *
     * @param string $prefix Form field name prefix, e.g. "bg" for bg_material.
     * @param string $extraField Either 'gender' or 'weight'.
     */
    private function buildSharedCategoryAttributes(Request $request, array $validated, string $category, string $prefix, string $extraField): ?array
    {
        if (($validated['category'] ?? '') !== $category) {
            return null;
        }

        $attrs = [
            'material' => $request->input("{$prefix}_material") ?: null,
            'color' => trim((string) $request->input("{$prefix}_color", '')) ?: null,
            'size' => trim((string) $request->input("{$prefix}_size", '')) ?: null,
            'warranty' => $request->input("{$prefix}_warranty") === 'Yes' ? 'Yes' : 'No',
            'warranty_duration' => $request->input("{$prefix}_warranty") === 'Yes'
                ? (trim((string) $request->input("{$prefix}_warranty_duration", '')) ?: null)
                : null,
        ];

        if ($extraField === 'gender') {
            $attrs['gender'] = $request->input("{$prefix}_gender") ?: null;
        } else {
            $attrs['weight'] = trim((string) $request->input("{$prefix}_weight", '')) ?: null;
        }

        return $attrs;
    }

    /** Queue a vendor-suggested category for admin review (deduped per vendor+name). */
    private function queueCategorySuggestion(array $validated, $productId)
    {
        try {
            $exists = DB::table('category_suggestions')
                ->where('user_id', Auth::id())
                ->where('category_name', $validated['category'])
                ->exists();

            if (!$exists) {
                DB::table('category_suggestions')->insert([
                    'user_id' => Auth::id(),
                    'category_name' => $validated['category'],
                    'subcategory_name' => $validated['subcategory'] ?? null,
                    'product_id' => $productId,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            // Suggestion logging must never block product creation
            \Log::warning('category_suggestions insert failed: ' . $e->getMessage());
        }
    }

    public function deleteProduct(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'product_id' => 'required'
            ]);
            
            // Find the product
            $product = VendorProduct::findOrFail($request->product_id);
            
            // Check if the product belongs to the current vendor
            if ($product->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to delete this product.'
                ], 403);
            }
            
            // Delete every image file for this product from BOTH storage trees
            // (the app double-writes to storage/app/public and public/storage).
            $imageRows = DB::table('vendor_product_images')
                ->where('product_id', $product->id)
                ->get();
            foreach ($imageRows as $img) {
                $file = $img->image_path ?? null;
                if (!$file) {
                    continue;
                }
                Storage::disk('public')->delete('vendor/products/images/' . $file);
                $publicCopy = public_path('storage/vendor/products/images/' . $file);
                if (is_file($publicCopy)) {
                    @unlink($publicCopy);
                }
            }

            // Remove image rows, then the product itself
            DB::table('vendor_product_images')->where('product_id', $product->id)->delete();
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => \App\Support\ErrorReason::friendly($e, 'Failed to delete product')
            ], 500);
        }
    }

    public function updateProduct(Request $request, $id)
    {
        // Find the product
        $product = VendorProduct::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->all();

        $isDraft = $request->boolean('save_as_draft');
        $wasDraft = $product->status === 'draft' || $product->position === 'draft';

        // "Other" category: use the vendor-typed value and queue it for admin review
        $customCategory = $this->resolveCustomCategory($request, $validated);

        // Men's Fashion: rebuild fashion_attributes from the submitted form.
        // Preserve the existing size_guide filename unless a new one is uploaded
        // (the form sends fa_size_guide_existing as a hidden field for that).
        // TODO: same pattern for Women's Fashion / Kids & Baby / Footwear / Bags & Accessories
        $fashionAttributes = $this->buildFashionAttributes($request, $validated);
        if ($fashionAttributes && !$request->hasFile('fa_size_guide') && $request->filled('fa_size_guide_existing')) {
            $fashionAttributes['size_guide'] = $request->input('fa_size_guide_existing');
        }

        // Jewellery & Accessories: rebuild jewelry_attributes from the submitted form.
        $jewelryAttributes = $this->buildJewelryAttributes($request, $validated);

        // Fragrance & Scents + the 5 shared-common-fields categories: rebuild
        // from the submitted form (same pattern as jewelry_attributes above).
        $fragranceAttributes = $this->buildFragranceAttributes($request, $validated);
        $bagsAttributes = $this->buildSharedCategoryAttributes($request, $validated, 'Bags & Luggage', 'bg', 'gender');
        $gymAttributes = $this->buildSharedCategoryAttributes($request, $validated, 'Personal Gym Accessories', 'gm', 'weight');
        $kitchenAttributes = $this->buildSharedCategoryAttributes($request, $validated, 'Kitchen & Dining', 'kt', 'weight');
        $smarthomeAttributes = $this->buildSharedCategoryAttributes($request, $validated, 'Smart Home & Gadgets', 'sm', 'weight');
        $personalcareAttributes = $this->buildSharedCategoryAttributes($request, $validated, 'Personal Care & Daily Essentials', 'pc', 'weight');

        // Update the product (drafts may have empty fields — keep old values as fallback)
        $product->update([
            'name' => ucwords(trim($validated['product_name'] ?? '')) ?: $product->name,
            'category' => $validated['category'] ?? $product->category,
            'subcategory' => $validated['subcategory'] ?? $product->subcategory,
            'quantity' => (int) (($validated['quantity'] ?? '') !== '' ? $validated['quantity'] : $product->quantity),
            'brand' => $validated['brand'] ?? $product->brand,
            'model' => $validated['model'] ?? $product->model,
            'pcondition' => $validated['condition'] ?? $product->pcondition,
            'original_price' => 0,
            'delivery_charges' => (float) (($validated['delivery_charges'] ?? '') !== '' ? $validated['delivery_charges'] : $product->delivery_charges),
            'free_delivery' => $request->boolean('free_delivery'),
            'selling_price' => (float) (($validated['selling_price'] ?? '') !== '' ? $validated['selling_price'] : $product->selling_price),
            'mrp' => ($validated['mrp'] ?? '') !== '' ? $validated['mrp'] : null,
            'shipping_method' => $validated['shipping_method'] ?? $product->shipping_method,
            'shipping_time' => $validated['shipping_time'] ?? $product->shipping_time,
            'description' => $validated['description'] ?? $product->description,
            'location' => $validated['location'] ?? $product->location,
            'made_in' => $validated['made_in'] ?? $product->made_in,
            'return_policy' => $validated['return_policy'] ?? null,
            'status' => $isDraft ? 'draft' : 'pending',
            'fashion_attributes' => $fashionAttributes ?: $product->fashion_attributes,
            'jewelry_attributes' => $jewelryAttributes ?: $product->jewelry_attributes,
            'fragrance_attributes' => $fragranceAttributes ?: $product->fragrance_attributes,
            'bags_attributes' => $bagsAttributes ?: $product->bags_attributes,
            'gym_attributes' => $gymAttributes ?: $product->gym_attributes,
            'kitchen_attributes' => $kitchenAttributes ?: $product->kitchen_attributes,
            'smarthome_attributes' => $smarthomeAttributes ?: $product->smarthome_attributes,
            'personalcare_attributes' => $personalcareAttributes ?: $product->personalcare_attributes,
        ]);

        // Men's Fashion: size guide/chart image (fashion_attributes.size_guide)
        $this->storeSizeGuideUpload($request, $product);

        if ($isDraft) {
            // Stays hidden from storefront + admin moderation queue
            $product->update(['position' => 'draft']);
        } elseif ($wasDraft) {
            // Publishing a draft -> enters the normal moderation queue
            $product->update(['position' => 'pending']);
        }
        // (non-draft edits never touch position — approved products stay live, as before)

        if ($customCategory) {
            $this->queueCategorySuggestion($validated, $product->id);
        }

        // Handle product images - REMOVE the ones the vendor deleted in the editor
        $deletedImageIds = array_filter((array) $request->input('deleted_images', []));
        if (!empty($deletedImageIds)) {
            $toDelete = VendorProductImage::where('product_id', $product->id)
                ->whereIn('id', $deletedImageIds)
                ->get();
            foreach ($toDelete as $img) {
                if ($img->image_path) {
                    Storage::disk('public')->delete('vendor/products/images/' . $img->image_path);
                    $publicCopy = public_path('storage/vendor/products/images/' . $img->image_path);
                    if (is_file($publicCopy)) {
                        @unlink($publicCopy);
                    }
                }
                $img->delete();
            }
            // If we removed the primary image, promote another one so the product still shows
            $hasPrimary = VendorProductImage::where('product_id', $product->id)->where('is_primary', 1)->exists();
            if (!$hasPrimary) {
                $next = VendorProductImage::where('product_id', $product->id)->orderBy('id')->first();
                if ($next) {
                    $next->update(['is_primary' => 1]);
                }
            }
        }

        // Handle product images - ADD NEW IMAGES ONLY
        if ($request->hasFile('productImages')) {
            foreach ($request->file('productImages') as $index => $image) {
                $extension = $image->getClientOriginalExtension();
                $filename = uniqid() . '.' . $extension;
                $imagePath = $image->storeAs('vendor/products/images', $filename, 'public');
                $destinationPath = public_path('storage/vendor/products/images');
                $image->move($destinationPath, $filename);
                
                VendorProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $filename,
                    'is_primary' => $index === 0 && $product->images()->count() === 0, // Primary only if no images exist
                ]);
            }
        }

        // Handle product video - REPLACE EXISTING VIDEO
        if ($request->hasFile('productVideo')) {
            // Delete old video if exists
            if ($product->video) {
                if (Storage::disk('public')->exists('vendor/products/videos/' . $product->video)) {
                    Storage::disk('public')->delete('vendor/products/videos/' . $product->video);
                }
            }

            $video = $request->file('productVideo');
            $extension = $video->getClientOriginalExtension();
            $filename = uniqid() . '.' . $extension;
            $videoPath = $video->storeAs('vendor/products/videos', $filename, 'public');
            $destinationPath = public_path('storage/vendor/products/videos');
            $video->move($destinationPath, $filename);
            
            $product->update([
                'video' => $filename
            ]);
        }

        // Handle remove video request
        if ($request->has('remove_video') && $request->input('remove_video')) {
            if ($product->video) {
                if (Storage::disk('public')->exists('vendor/products/videos/' . $product->video)) {
                    Storage::disk('public')->delete('vendor/products/videos/' . $product->video);
                }
            }
            $product->update(['video' => null]);
        }

        // Handle product faults - DELETE OLD AND ADD NEW
        // First delete all existing faults
        $existingFaults = VendorProductFault::where('product_id', $product->id)->get();
        foreach ($existingFaults as $fault) {
            if ($fault->fault_image && Storage::disk('public')->exists('vendor/products/faults/' . $fault->fault_image)) {
                Storage::disk('public')->delete('vendor/products/faults/' . $fault->fault_image);
            }
            $fault->delete();
        }

        // Then add new faults
        if ($request->hasFile('faults')) {
            foreach ($request->file('faults') as $index => $faultImage) {
                $extension = $faultImage->getClientOriginalExtension();
                $filename = uniqid() . '.' . $extension;
                $faultImagePath = $faultImage->storeAs('vendor/products/faults', $filename, 'public');
                $destinationPath = public_path('storage/vendor/products/faults');
                $faultImage->move($destinationPath, $filename);
                
                VendorProductFault::create([
                    'product_id' => $product->id,
                    'fault_image' => $filename,
                    'fault_description' => $validated['fault_descriptions'][$index] ?? null,
                ]);
            }
        }

        $message = $isDraft
            ? 'Draft saved. You can finish and publish it anytime from My Products.'
            : ($wasDraft
                ? 'Product published successfully and is pending approval.'
                : 'Product updated successfully and is pending approval.');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('vendor.products')
            ]);
        }

        return redirect()->route('vendor.products')
            ->with('success', $message);
    }

    public function updateStatus(Request $request) {
        try {
            $request->validate([
                'user_id' => 'required|integer|exists:users,user_id',
                'status' => 'required|in:active,pending,blocked',
                'action' => 'required|in:approve,block,unblock,reject'
            ]);
            
            $userId = $request->user_id;
            $newStatus = $request->status;
            $action = $request->action;
            $verify = ($newStatus == 'active') ? 1 : 0;
            
            // Update user status
            DB::table('users')
                ->where('user_id', $userId)
                ->update(['status' => $newStatus, 'verified' => $verify]);
            
            return response()->json([
                'success' => true
            ]);
            // Log the action if needed
            // ActivityLog::create([...]);

            return response()->json([
                'success' => true,
                'message' => "Vendor {$action}d successfully!",
                'data' => [
                    'user_id' => $userId,
                    'new_status' => $newStatus,
                    'action' => $action
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => \App\Support\ErrorReason::friendly($e, 'Failed to update vendor status')
            ], 500);
        }
    }

    public function getVendorDetails($user_id)
    {
        try {
            $user_id = intval($user_id);

            // Get vendor basic info
            $vendor = DB::table('users as u')
                ->leftJoin('vendor_basic_info as vbi', 'u.user_id', '=', 'vbi.user_id')
                ->leftJoin('vendor_store_details as vsd', 'u.user_id', '=', 'vsd.user_id')
                ->leftJoin('vendor_address as va', 'u.user_id', '=', 'va.user_id')
                ->where('u.user_id', $user_id)
                ->select('u.*', 'vbi.*', 'vsd.*', 'va.*')
                ->first();

            if (!$vendor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor not found'
                ], 404);
            }

            // Get vendor products
            $products = DB::table('vendor_products')
                ->where('user_id', $user_id)
                ->limit(5)
                ->get();

            // Total earnings = sum of delivered line items for this vendor's products
            $earnings = (object) [
                'total_earnings' => (float) DB::table('carts as c')
                    ->join('vendor_products as vp', 'c.product_id', '=', 'vp.id')
                    ->where('vp.user_id', $user_id)
                    ->where('c.status', 'delivered')
                    ->sum(DB::raw('c.price * c.quantity'))
            ];

            // // Get vendor orders
            // $orders = DB::table('orders as o')
            //     ->join('vendor_products as p', 'o.product_id', '=', 'p.id')
            //     ->where('o.vendor_id', $user_id)
            //     ->select('o.*', 'p.name as product_name')
            //     ->orderBy('o.order_date', 'DESC')
            //     ->limit(5)
            //     ->get();

            // // Get vendor documents
            // $documents = DB::table('vendor_documents')
            //     ->where('user_id', $user_id)
            //     ->get();

            // // Get vendor payments
            // $payments = DB::table('vendor_payments')
            //     ->where('user_id', $user_id)
            //     ->orderBy('payment_date', 'DESC')
            //     ->limit(5)
            //     ->get();

            // // Calculate total earnings
            // $earnings = DB::table('orders')
            //     ->where('vendor_id', $user_id)
            //     ->select(DB::raw('SUM(total_amount) as total_earnings'))
            //     ->first();


            // return response()->json([
            //     'success' => true,
            //     'html' => [$user_id, $vendor, $products]
            // ]);

            // Generate HTML content.
            // NOTE: capital "Admin" — the directory is resources/views/Admin.
            // Windows resolves this case-insensitively, but the live host is
            // Linux, where the lowercase form 404s.
            $html = view('Admin/vendor_details', compact([
                'vendor', 'products', 'earnings'
            ]))->render();

            return response()->json([
                'success' => true,
                'html' => $html
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => \App\Support\ErrorReason::friendly($e, 'Error loading vendor details')
            ], 500);
        }
    }

    public function pr ($pr = 1) {}

    public function orders() {
        $user = Auth::user();
        $vendor_id = $user->user_id;
        
        // Get all product IDs for this vendor
        $productIds = DB::table('vendor_products')
            ->where('user_id', $vendor_id)
            ->pluck('id')
            ->toArray();

        // Get all carts that have these product IDs
        $orders = DB::table('carts')
            ->whereIn('product_id', $productIds)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate stats
        $totalOrders = $orders->count();
        $deliveredOrders = $orders->where('status', 'delivered')->count();
        $paidOrders = $orders->where('status', 'paid')->count();
        $activeOrders = $orders->whereNotIn('status', ['delivered', 'cancelled'])->count();

        // Get vendor basic info
        $vendorBasicInfo = DB::table('vendor_basic_info')
            ->where('user_id', $vendor_id)
            ->first();

        return view('vendor.orders', compact(
            'user',
            'orders',
            'totalOrders',
            'deliveredOrders',
            'paidOrders',
            'activeOrders',
            'vendorBasicInfo'
        ));
    }

    public function updateOrderStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|exists:carts,id',
            'status' => 'required|in:order placed,processing,shipping,delivered,cancelled'
        ]);

        $vendor_id = Auth::id();
        
        // Check if the product belongs to this vendor
        $isValid = DB::table('carts')
            ->join('vendor_products', 'carts.product_id', '=', 'vendor_products.id')
            ->where('carts.id', $request->order_id)
            ->where('vendor_products.user_id', $vendor_id)
            ->exists();

        if (!$isValid) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        // Update status
        DB::table('carts')
            ->where('id', $request->order_id)
            ->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);

        // Notify the customer about the status change (best-effort; never break the update)
        try {
            $cart = DB::table('carts')->where('id', $request->order_id)->first();
            if ($cart && $cart->user_id) {
                $productName = DB::table('vendor_products')->where('id', $cart->product_id)->value('name') ?? 'Product';
                DB::table('notifications')->insert([
                    'user_id' => $cart->user_id,
                    'title' => 'Order update',
                    'message' => "Your order item \"{$productName}\" is now: " . ucwords($request->status) . ".",
                    'type' => 'order',
                    'icon_class' => 'fas fa-bell',
                    'icon_color' => 'bg-pink-100 text-[#E85D85]',
                    'dot_color' => 'bg-[#FF7DA0]',
                    'is_read' => 0,
                    'created_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            \Log::warning('order status notify failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'status' => $request->status
        ]);
    }





    public function notifications()
    {
        $user = Auth::user();
        // dd($user->full_name);
        $vendor_id = $user->user_id;

        $vendorBasicInfo = DB::table('vendor_basic_info')
            ->where('user_id', $vendor_id)
            ->first();
        
        // Get notifications grouped by date
        $notifications = DB::table('notifications')
            ->where('user_id', $user->user_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($notification) {
                return $this->formatDateGroup($notification->created_at);
            });
        
        return view('vendor.notifications', compact('user', 'vendor_id', 'vendorBasicInfo', 'notifications'));
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', $user->user_id)
            ->update([
                'is_read' => 1,
                'read_at' => now()
            ]);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Format date for grouping
     */
    private function formatDateGroup($date)
    {
        $notificationDate = \Carbon\Carbon::parse($date);
        $today = \Carbon\Carbon::today();
        $yesterday = \Carbon\Carbon::yesterday();
        
        if ($notificationDate->isToday()) {
            return 'Today';
        } elseif ($notificationDate->isYesterday()) {
            return 'Yesterday';
        } elseif ($notificationDate->isCurrentWeek()) {
            return 'This Week';
        } else {
            return $notificationDate->format('F Y');
        }
    }

}
